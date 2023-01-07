<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Usermember;
use frontend\models\Membership;
use frontend\models\Userbot;
use frontend\models\Userpay;
use frontend\models\Pricelist;
use frontend\models\Botsignal;
use frontend\models\Cryptoaddress;
use common\helpers\GeneralHelper;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use common\helpers\coinqvest\CQMerchantClient;

/**
 * Membership controller
 */
class MembershipController extends Controller
{
  /**
  * @var boolean whether to enable CSRF validation for the actions in this controller.
  * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
  */
  public $enableCsrfValidation = false;

  /**
  * @inheritdoc
  */
  public function behaviors()
  {
		$access = GeneralHelper::checkSiteAccess();
		Yii::trace('** behavior BotsignalController: '.print_r($access, true));
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          /*[
            'allow' => true,
            'actions' => ['index', 'view', 'create', 'update', 'delete'],
            'roles' => ['BackendBotFull'],
          ],
          [
            'allow' => true,
            'actions' => ['index', 'view'],
            'roles' => ['BackendBotView'],
          ],
          [
            'allow' => true,
            'actions' => ['update', 'create', 'delete'],
            'roles' => ['BackendBotEdit'],
          ],*/
          [
            'allow' => ($access['frontend'] == 'true'),
            'roles' => ['@'],  // Allow authenticated/loged in users
          ],
          [
            'allow' => true,
            'actions' => ['paypal', 'paydone', 'payerror', 'checkout', 'makepayment', 'molliechangestate', 'coinqvestchangestate', 'getcryptoprice', 'getcryptocodes', 'utrustcallback'],
            'roles' => ['?'],
          ],

          // anybody else is denied
        ],
      ],
    ];
  }

// --------------------------------------

	public function actionGetcryptoprice()
	{
		$result = [];
		try {
			Yii::trace('** actionGetcryptoprice POST: ' . print_r($_POST, true));
			if (!empty($_POST) && !empty($_POST['quote']) && !empty($_POST['base'])) {
				$result['price'] = GeneralHelper::getQuoteBasePrice($_POST['quote'], $_POST['base'], false);
			}
		} catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $result['Error'] = $msg;
    }
		Yii::trace('** actionGetcryptoprice result: ' . print_r($result, true));
		return json_encode($result);
	}

	public function actionGetcryptocodes()
	{
		$result = [];
		try {
			Yii::trace('** actionGetcryptocodes POST: ' . print_r($_POST, true));
			if (!empty($_POST) && !empty($_POST['prlcadIds']) && !empty($_POST['payvia'])) {
				$result['options'] = Cryptoaddress::getCryptoToAddresses($_POST['prlcadIds'], $_POST['payvia'], false);
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			$result['Error'] = $msg;
		}
		Yii::trace('** actionGetcryptocodes result: ' . print_r($result, true));
		return json_encode($result);
	}

// --------------------------------------
  /**
  * index show all memberships for current language
  * @return mixed
  */
  public function actionIndex()
  {
		$usermembersData = Usermember::getUsermembersOfUser(\Yii::$app->user->id, false, false, true);
		Yii::trace('** actionIndex usrid='.(\Yii::$app->user->id).' -> usermembersData: '.print_r($usermembersData, true));

		$umbids = implode(',', array_keys($usermembersData));
		$userpaysData = Userpay::getPaymentsOfUsermembers($umbids, true, false, true);
		Yii::trace('** actionIndex umbids='.$umbids.' => userpaysData: '.print_r($userpaysData, true));

		$userbotsData = Userbot::getUserbotsOfUsermembers($umbids);
		Yii::trace('** actionIndex umbids='.$umbids.' => userbotsData: '.print_r($userbotsData, true));

    return $this->render('index', [
			'usermembersData' => $usermembersData,
			'userpaysData' => $userpaysData,
			'userbotsData' => $userbotsData
		]);
  }

  public function actionHistory()
  {
    //$usermembersModel = Usermember::getHistoryUsermemberships( \Yii::$app->user->id, false);
    //return $this->render('history', ['usermembersModel' => $usermembersModel]);

    $usermembersData = Usermember::getUsermembersOfUser(\Yii::$app->user->id, false, true, false);
    Yii::trace('** actionHistory usrid='.(\Yii::$app->user->id).' -> usermembersData: '.print_r($usermembersData, true));

    $umbids = implode(',', array_keys($usermembersData));
    $userpaysData = Userpay::getPaymentsOfUsermembers($umbids, true, true, false);
    Yii::trace('** actionHistory umbids='.$umbids.' => userpaysData: '.print_r($userpaysData, true));

    $userbotsData = Userbot::getUserbotsOfUsermembers($umbids);
    Yii::trace('** actionHistory umbids='.$umbids.' => userbotsData: '.print_r($userbotsData, true));

    return $this->render('index-history', [
      'usermembersData' => $usermembersData,
      'userpaysData' => $userpaysData,
      'userbotsData' => $userbotsData
    ]);
  }


	public function actionUmblist()
  {
    $usermembersModel = Usermember::getUsermembersOfUser(\Yii::$app->user->id, false);
    return $this->render('umblist', ['usermembersModel' => $usermembersModel]);
  }

// -----------------------------------

  /**
  * Join-in a new member.
  * @return mixed
  */
  public function actionSubscribe()
  {
		$language = Yii::$app->user->identity->language;
		$membershipModels = Membership::findModelByCodeAndLanguage('', $language, \Yii::$app->user->id);
		$errors = [];

		try {
			if (!empty($_POST) && !empty($_POST['prlid']) && !empty($_POST['mbrid'])) {
				$prlid = $_POST['prlid'];
				$pricelist = Pricelist::find()->select(['prl_percode', 'prl_maxsignals'])->where(['id'=>$prlid])->one();
				$mbrid = $_POST['mbrid'];
				$usrid = \Yii::$app->user->id;
				Yii::trace('** actionSubscribe prlid='.$prlid.' percode='.$pricelist->prl_percode.' mbrid='.$mbrid.' usrid='.$usrid.' maxsignals='.$pricelist->prl_maxsignals);

				$ok = false;
				$memberships = ArrayHelper::toArray($membershipModels, ['\backend\models\Membership' => ['id', 'mbr_title']]);
				foreach( $memberships as $nr => $membership) {
					//Yii::trace('** actionSubscribe membership id='.$membership['id']);
					if ($membership['id'] == $mbrid) {
						Yii::trace('** actionSubscribe selected '.$nr.'/'.(count($memberships)).': mbrid='.$mbrid.' = '.$membership['mbr_title']);

						$currentMembership = Usermember::findUsermemberForUserAndMembership($usrid, $mbrid); // Usermember::findPaidUsermemberForUser($usrid, $mbrid, false);
						if (!empty($currentMembership) /*&& (GeneralHelper::showDateTime($currentMembership['upy_enddate'],'unix') >= mktime(0,0,0))*/ ) { // current period ends today or later
							//$newstart = (GeneralHelper::showDateTime($currentMembership['umb_enddate'],'unix') >= mktime(0,0,0));
							//$startdate = GeneralHelper::getStrToTimePricelistPeriod(Pricelist::PRL_PERCODE_1D, $currentMembership['upy_enddate'], false, true); // day after last paid enddate, minimal today
							$lastenddate = (!empty($currentMembership['lastenddate']) ? $currentMembership['lastenddate'] : date('Y-m-d'));
							Yii::trace('** actionSubscribe lastenddate='.$lastenddate);
							$startdate = GeneralHelper::getStrToTimePricelistPeriod(Pricelist::PRL_PERCODE_1D, $lastenddate, false, true); // day after last paid enddate, minimal today
							Yii::trace('** actionSubscribe startdate: '.$startdate);
							$usermemberModel = Usermember::findOne( $currentMembership['id'] );
						} else {
							$usermemberModel = new Usermember;
							$startdate = date('Y-m-d'); // start today
							$usermemberModel->umbmbr_id = $mbrid; //$membershipModel->id;
							$usermemberModel->umb_active = 0; // initial inactive , active when paid
    	    		$usermemberModel->umb_createdat = time();
							$usermemberModel->umbusr_id = $usermemberModel->umbusr_created_id = $usrid;
							$usermemberModel->umb_createdt = date('Y-m-d H:i:s', time());
						}
						$usermemberModel->umbupy_id = null; // reset for new payment; used in pay cycle to know which upy.id is used to re select payProvider when re enter that screen after cancel
						$usermemberModel->umbprl_id = $prlid; // last one for userpay when paid
						$usermemberModel->umb_name = $pricelist->prl_percode .' '. $membership['mbr_title'];  //Yii::$app->user->identity->username;
						//$usermemberModel->umb_maxsignals = $pricelist->prl_maxsignals;

						$usermemberModel->umb_paystartdate = $startdate; // start new pay period (optional in future)
						$usermemberModel->umb_payenddate = GeneralHelper::getStrToTimePricelistPeriod($pricelist->prl_percode, $startdate, true);

						$usermemberModel->umb_updatedat = time();
      	  	$usermemberModel->umbusr_updated_id = $usrid;
        		$usermemberModel->umb_updatedt = date('Y-m-d H:i:s', time());
        		if ($usermemberModel->save()) { $ok = true; }
						Yii::trace('** actionSubscribe saved! ok='.($ok ? 'OK' : 'NotOK='.print_r( $usermemberModel->getErrors(),true)));
		      	if ($ok) {
							return $this->redirect(['pay', 'id' => $usermemberModel->id]); // next step, even if free: must accept Memebrship-terms
      			} else {
							$errors[] = 'Error saving data!';
						}
						break;
					}
      	}
				if (empty($errors)) $errors[] = 'Error finding membership!';
    	}
   	} catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $errors[] = 'Error '.$msg;
			Yii::trace('** actionSubscribe ERROR ' . $msg);
    }
		Yii::trace('** actionSubscribe Errors: '.print_r($errors, true));
    return $this->render('subscribe', [
			'membershipModels' => $membershipModels,
			'errors' => $errors,
		]); // this step
	}

// --------------------------------------

	public function actionMolliechangestate() // webhook function
	{
		try {
			Yii::trace('** actionMolliechangestate : GET='.$_GET.' POST='.print_r($_POST,true));
			if (!empty($_POST) && !empty($_POST['id'])) {
				$payment = $mollie->payments->get($_POST['id']);
				$payReference = $payment->metatdata->pay_reference;
				$status = Userpay::UPY_STATE_UNKNOWN;
				$remarks = '';
				if ($payment->isPaid() && ! $payment->hasRefunds() && ! $payment->hasChargebacks()) {
    	    // The payment is paid and isn't refunded or charged back. At this point you'd probably want to start the process of delivering the product to the customer.
					$status = Userpay::UPY_STATE_PAID;
				} elseif ($payment->isOpen()) {
  	      // The payment is open.
					$status = Userpay::UPY_STATE_OPEN;
				} elseif ($payment->isPending()) {
        	// The payment is pending.
					$status = Userpay::UPY_STATE_PENDING;
				} elseif ($payment->isFailed()) {
      	  // The payment has failed.
					$status = Userpay::UPY_STATE_FAILED;
				} elseif ($payment->isExpired()) {
  	      // The payment is expired.
					$status = Userpay::UPY_STATE_EXPIRED;
				} elseif ($payment->isCanceled()) {
        	// The payment has been canceled.
					$status = Userpay::UPY_STATE_CANCEL;
				} elseif ($payment->hasRefunds()) {
    	    // The payment has been (partially) refunded. The status of the payment is still "paid"
					$status = Userpay::UPY_STATE_PAID; //HASREFUNDS;
					$remarks = 'Has refunds.';
				} elseif ($payment->hasChargebacks()) {
  	      // The payment has been (partially) charged back. The status of the payment is still "paid"
					$status = Userpay::UPY_STATE_PAID; //HASCHARGEBACKS;
					$remarks = 'Has chargebacks.';
				}
				Yii::trace('** actionMolliechangestate payReference='.$payReference.' status='.$status.' ');
				// update payment
				if (!empty($payreference)) {
					$sql = "UPDATE userpay SET upy_payreply='".$payment->status."', upy_state='".$status."',".(!empty($remarks) ? " upy_remarks=CONCAT(upy_remarks,'".$remarks."\n')," : "")
								." upy_updatedat=".time().", upy_updatedt='".date('Y-m-d H:i:s', time())."', upyusr_updated_id=1"
								." WHERE upy_payref='".$payReference."'";
					$result = GeneralHelper::runSql($sql);
					Yii::trace('** actionMolliechangestate result='.$result);
				}
			}
		} catch (\Mollie\Api\Exceptions\ApiException $e) {
			$msg = "Mollie API call failed: " . htmlspecialchars($e->getMessage());
			Yii::trace('** actionMolliechangestate error msg='. $msg);
		}
	}

	public function actionCoinqvestchangestate() // webhook function
	{
		try {
			Yii::trace('** actionCoinqvestchangestate post: '.print_r($_POST, true));

			// todo

		} catch (\Exception $e) {
      $msg = "Coinqvest API call failed: " . htmlspecialchars($e->getMessage());
      Yii::trace('** actionCoinqvestchangestate error msg='. $msg);
    }
	}

	public function actionUtrustcallback($ref='')
	{
		try {
			Yii::trace('** actionUtrustcallback ref='.$ref.' post: '.print_r($_POST, true));
			if (!empty($ref)) {

				Yii::trace('** actionUtrustcallback *** *** *** *** *** TODO *** Notify user *** *** *** *** *** ToDo *** *** *** ***');

			}
		} catch (\Exception $e) {
			$msg = "Utrust callback ERROR ref=".$ref." => " . htmlspecialchars($e->getMessage());
			Yii::trace('** actionUtrustcallback msg='. $msg);
    }
	}

	public function actionCheckdiscount() {
		$result = '';
		if (!empty($_POST)) {
			Yii::trace('** actionCheckdiscount POST: '.print_r($_POST, true));
			$discountcode = (!empty($_POST['discountcode']) ? $_POST['discountcode'] : '');
			$mbrid = (!empty($_POST['mbrid']) ? $_POST['mbrid'] : 0);
			$usrid = ((!empty($_POST['usrid']) && ($_POST['usrid'] == \Yii::$app->user->id)) ? $_POST['usrid'] : 0); // only if same as loggedin user
			$symid = (!empty($_POST['symid']) ? $_POST['symid'] : 0);
			$percode = (!empty($_POST['percode']) ? $_POST['percode'] : '');
			$result = GeneralHelper::checkDiscountcode($mbrid, $usrid, $symid, $percode, $discountcode);
		}
		Yii::trace('** actionCheckdiscount result:'.print_r($result,true));
		return json_encode($result);
	}

// ---
	protected function createReference() {
		return uniqid('btg', true);
	}

	public function actionPay($id) // into usermember
	{
		$userpayModel = new Userpay;
		$usermemberModel = null; //new Usermember;

		try {
			Yii::trace('** actionPay id='.$id);
			if (!empty($id) && (($usermemberModel = Usermember::findOne($id)) !== null)) {
				// valid entry from subscribe
				if ($usermemberModel->umbusr_id == \Yii::$app->user->id) {
					// same user selected subscription aa logged-in
					$usrid = $usermemberModel->umbusr_id;
					$mbrid = $usermemberModel->umbmbr_id;
					$ok = false;
					$fiatprice = 0; $fiatcode = '';

					if (!empty($usermemberModel->umbupy_id)) {
						// re set for form if re entered after cancel or when available..
						$userpayModel->upy_payprovider = $usermemberModel->umbupy->upy_payprovider;
					}

					$membershipQuantitiesForUser = GeneralHelper::getMembershipQuantitiesForUser($usrid);
					$mbrQuantity = (!empty($membershipQuantitiesForUser[ $mbrid ]) ? $membershipQuantitiesForUser[ $mbrid ] : 0);
					$allowedtimes = $usermemberModel->umbprl->prl_allowedtimes;
					Yii::trace('** actionPay mbrid='.$mbrid.' mbrQuantity='.$mbrQuantity.' <= allowedtimes='.$allowedtimes);
					if (($allowedtimes == 0) || ($mbrQuantity < $allowedtimes)) {
						// Allow payment due count
						$payReference = self::createReference();
						$getPricelistPeriods = GeneralHelper::getPricelistPeriods();

						$ok = (!empty($_POST));
						if ($ok) {
							// Payment selected at this page
							$fiatprice = 0+$usermemberModel->umbprl->prl_price; // in FIAT
							Yii::trace('** actionPay fiatprice='.$fiatprice.' POST: '.print_r($_POST,true));

							$payProvider = (($fiatprice != 0) ? $_POST['Userpay']['upy_payprovider'] : GeneralHelper::PAYPROVIDER_NONE);
							$percode = $usermemberModel->umbprl->prl_percode;
							$periodCodeText = $getPricelistPeriods[ $percode ];
							$fiatcode = strtoupper($usermemberModel->umbprl->prlsym->sym_code); // FIAT
							$symid = $usermemberModel->umbprl->prlsym_id; // FIAT
							$prlid = $usermemberModel->umbprl_id;

							// Check Discount
							$discountcode = ''.$_POST['Userpay']['upy_discountcode'];
							$usedcount = $allowedtimes = -1; // invalid
							if (!empty($discountcode)) {
								$discountResult = GeneralHelper::checkDiscountcode($mbrid, $usrid, $symid, $percode, $discountcode);
								/*if (!empty($discountResult['usedcount']))*/    $usedcount = $discountResult['usedcount'];
								/*if (!empty($discountResult['allowedtimes']))*/ $allowedtimes = $discountResult['allowedtimes'];
								if (($usedcount > -1) && ($allowedtimes > -1)) {
									if (($allowedtimes == 0) || ($usedcount < $allowedtimes)) {
										if (($discountResult['price'] != 'na') && is_numeric($discountResult['price'])) {
											$prlid = $discountResult['prlid'];
											$fiatprice = $discountResult['price'];
											$discountcode .= '|valid';
										} else {
											$discountcode .= '|invalid';
										}
									} else {
										$discountcode .= '|already';
									}
								} else {
									$discountcode .= '|error';
									$ok = false;
								}
							}
							Yii::trace('** actionPay discount result: discountcode='.$discountcode.' price='.$fiatprice.' usedcount='.$usedcount.' allowedtimes='.$allowedtimes);

							if ($ok) {
								$mbrTitle = $usermemberModel->umbmbr->mbr_title;
								$payDescription = $mbrTitle . (!empty($periodCodeText) ? Yii::t('app', ' for ') . $periodCodeText : '');
								if (empty($payDescription)) $payDescription = Yii::$app->name .' '. Yii::t('app', 'membership');

								$returnUrl = Url::to('/membership/paydone?ref='.$payReference, true);

								// pre-save actions
								$providerMode = $providerid = $cqRedirUrl = $cryptoData = '';	$cryptovalid = false; $cryptoprice=0;

								// cryptoprice calc..
								switch ($payProvider) {
									case GeneralHelper::PAYPROVIDER_UTRUST:
									case GeneralHelper::PAYPROVIDER_CRYPTODIRECT:
										try {
											if (!empty($_POST['Userpay']['upycad_to_id'])) {
												list($cadid, $cryptocode, $decimals) = explode('|', $_POST['Userpay']['upycad_to_id']);
												$cadData = Cryptoaddress::getCadData($cadid);
												Yii::trace('** actionPay 1 POST='.print_r($_POST, true).' => cadData: '.print_r($cadData, true));
												if (empty($cadData['error'])) {
													$cryptovalid = true;
													$cadsymid = $cadData['cadsymid'];
													$pricedata = GeneralHelper::getQuoteBasePrice($cryptocode, $fiatcode, false);
													Yii::trace('** actionPay 1 pricedata: '.print_r($pricedata, true));
													if (!empty($pricedata) && !empty($pricedata['price'])) {
														$cryptoprice = $fiatprice / $pricedata['price'];
													}
												}
											}
										} catch (\Mollie\Api\Exceptions\ApiException $e) {
                      $msg = '** actionPay 1 Cryptoprice calc failed : ' . $e->getMessage();
                      Yii::trace($msg);
                      $userpayModel->addError('_exception', $msg);
                      $ok = false;
                    }
                    break;
								}

								switch ($payProvider) {
									case GeneralHelper::PAYPROVIDER_PAYPAL: $providerMode = Yii::$app->params['payPalMode']; break;

									case GeneralHelper::PAYPROVIDER_MOLLIE:
										try {
											$providerMode = Yii::$app->params['mollie_mode'];
											$mollie = new \Mollie\Api\MollieApiClient();
											$mollie->setApiKey( Yii::$app->params['mollie_APIkey_' . Yii::$app->params['mollie_mode']] );

											$mollieOrder = [
												"amount" => [
													"currency" => "EUR",
													"value" => number_format((float)$fiatprice,2,'.',','),
												],
												"description" => $payDescription,
												"redirectUrl" => $returnUrl,
												"webhookUrl"  => Yii::$app->urlManager->createAbsoluteUrl('/membership/molliechangestate'),
												"metadata" => [
													"pay_reference" => $payReference,
												],
											];
											Yii::trace('** actionPay mollieOrder:'.print_r($mollieOrder,true));
											$payment = $mollie->payments->create( $mollieOrder );
											$providerid = $payment->id;
											$ok = (!empty($providerid));
											Yii::trace('** actionPay ok='.($ok ? 'Ok':'NotOk').' providerid='.$providerid);
										} catch (\Mollie\Api\Exceptions\ApiException $e) {
											$msg = '** actionPay 1 MOLLIE API call failed : ' . $e->getMessage();
											Yii::trace($msg);
											$userpayModel->addError('_exception', $msg);
											$ok = false;
										}
										break;

									case GeneralHelper::PAYPROVIDER_UTRUST:
										try {
											$providerMode = Yii::$app->params['cryptoutrust_mode'];

											$cryptovalid = false; // Only fiat
											$utrustprice = (!$cryptovalid ? number_format((float)$fiatprice,2,'.','') : number_format((float)$cryptoprice,$cadData['decimals'],'.',''));
											$utrustsymbol = (!$cryptovalid ? 'EUR' : strtoupper($cadData['symbol']));
											$userModel = User::find()->select('username,email')->where(['id'=>$usrid])->one();
											if ($userModel) {
												if (!empty($userModel->usr_firstname) && !empty($userModel->usr_lastname) && !empty($userModel->usr_countrycode)) {
													$utrustData = [
														'data' => [
															'type' => 'orders',
															'attributes' => [
																'order' => [
																	'reference' => $payReference, // required
																	'amount' => [
																		'total' => $utrustprice, // required
																		'currency' => $utrustsymbol, // required, 3 letters
																		/*'details' => [
																			'subtotal' => '',
																			'tax' => '',
																			'shipping' => '',
																			'discount' => '',
																		],*/
																	],
																	'return_urls' => [
																		'return_url'   => $returnUrl.'&success=true&ref='.$payReference,  //Yii::$app->urlManager->createAbsoluteUrl('/membership/utrustreturn?success=ok&ref='.$payReference),   // '/order_success', // required, url
																		'cancel_url'   => $returnUrl.'&success=false&ref='.$payReference,  //Yii::$app->urlManager->createAbsoluteUrl('/membership/utrustreturn?success=cancel&ref='.$payReference), // '/order_canceled',
																		'callback_url' => Yii::$app->urlManager->createAbsoluteUrl('/membership/utrustcallback?ref='.$payReference), // '/webhook_url',
																	],
																	'line_items' => [
																		[
																			//'sku' => '',
																			'name' => $payDescription, // required
																			'price' => $utrustprice, //number_format((float)$cryptoprice,$cadData['decimals'],'.',''), // required
																			'currency' => $utrustsymbol, //strtoupper($cadData['symbol']), // required, 3 letters
																			'quantity' => 1, // required, integer
																		],
																	],
																],
																'customer' => [ // required
																	'first_name' => $userModel->usr_firstname, // required
																	'last_name' => $userModel->usr_lastname, // required
																	'email' => $userModel->email, // required, email
															    'country' => strtoupper($userModel->usr_countrycode),  // required, 2 letter code
																	/*'address1' => '',
																	'address2' => '',
																	'city' => '',
																	'state' => '',
																	'postcode' => '',*/
																],
															],
														],
													];
													Yii::trace('** actionPay 1 UTRUST data: '.print_r($utrustData, true));
												} else {
													$msg = 'Your first- / lastname and/or country not given! Please update your profile.';
													Yii::trace('** actionPay 1 UTRUST Error '.$msg);
													$userpayModel->addError('Error', Yii::t('app', $msg));
													$ok=false;
												}
											} else {
												$msg = 'User ('.$usrid.') not found';
												Yii::trace('** actionPay 1 UTRUST Error '.$msg);
												$userpayModel->addError('Error', $msg);
												$ok=false;
											}
											//$utrustValid = true; // if all required fields are filled

										} catch (\Mollie\Api\Exceptions\ApiException $e) {
											$msg = '** actionPay 1 UTRUST API call failed : ' . $e->getMessage();
											Yii::trace($msg);
											$userpayModel->addError('_exception', $msg);
											$ok = false;
                    }
										break;

									case GeneralHelper::PAYPROVIDER_COINQVEST:
										try {
											$ok = false;
											if ($cryptovalid) {
												$providerMode = Yii::$app->params['cryptodirect_coinqvest_mode'];
												$apikey = Yii::$app->params['cryptodirect_coinqvest_apikey'];
												$apisecret = Yii::$app->params['cryptodirect_coinqvest_apisecret'];
												$cqOrder = [
													"charge" => [
														"currency" => strtoupper($cadData['symbol']),
														"lineItems" => [
															[
																"description" => $payDescription,
																"netAmount" => number_format((float)$cryptoprice,$cadData['decimals'],'.',''),
																"quantity" => 1,
																"productId" => $payReference
															]
														]
													],
													"settlementCurrency" => strtoupper($cadData['symbol']),
													"webhook" => Yii::$app->urlManager->createAbsoluteUrl('/membership/coinqvestchangestate')
												];
												Yii::trace('** actionPay 1 Coinqvest cqOrder='.print_r($cqOrder, true));

												$cqClient = new CQMerchantClient( $apikey, $apisecret, 'coinqvest.log');
												$cqResponse = $cqClient->post('/checkout', $cqOrder);
												$ok = ($cqResponse->httpStatusCode == 200);
												if ($ok) {
													$ok = false;
													// code for /checkout/hosted
													//$cqData = json_decode($cqResponse->responseBody, true);
													//$providerid = $cqData['id']; // store this persistently in your database
													//$cqRedirUrl = $cqData['url']; // redirect customer to this URL to complete the payment
													//Yii::trace('** actionPay 1 Coinqvest checkoutId='.$providerid.' redirurl='.$cqRedirUrl);
													// code for /checkout..
													Yii::trace('** actionPay 1 Coinqvest checkout response:'.print_r($cqResponse,true));
													Yii::trace('** actionPay 1 Coinqvest checkout responseBody='.$cqResponse->responseBody);
													if (!empty($cqResponse->responseBody)) {
														$payreply['checkout'] = json_decode($cqResponse->responseBody, true);
														Yii::trace('** actionPay 1 Coinqvest payReply='.print_r($payreply['checkout'], true));
														if (!empty($payreply['checkout']['id'])) {
															$providerid = $payreply['checkout']['id'];
															foreach($payreply['checkout']['paymentMethods'] as $paymentMethod) {
																if ($paymentMethod['assetCode'] == $cryptocode) {
																	$cqCommit = [
																		'checkoutId' => $providerid,
																		'assetCode' => $paymentMethod['assetCode']
																	];
																	$cqResponse = $cqClient->post('/checkout/commit', $cqCommit);
																	Yii::trace('** actionPay 1 Coinqvest commit response:'.print_r($cqResponse, true));
																	if (!empty($cqResponse->httpStatusCode == 200)) {
																		$cryptoData = json_decode($cqResponse->responseBody, true);
																		$cryptoData['payReference'] = $payReference;
																		$payreply['commit'].= $cryptoData;
																		$ok=true;
																	}
																}
															}
														}
													}
												}
											}
											if (!$ok) {
												$msg = (!$cryptovalid ? 'No crypto price' : (!empty($cqResponse->curlErrNo) ? $cqResponse->curlErrNo .': '. $cqResponse->curlError : $cqResponse->httpStatusCode));
												$userpayModel->addError('_exception', $msg);
												Yii::trace('** actionPay 1 Coinqvest ERROR payMethods: ' . $msg);
											}
										} catch (\Exception $e) {
                      $msg = '** actionPay 1 CryptoDirect API call failed : ' . $e->getMessage();
                      Yii::trace($msg);
                      $userpayModel->addError('_exception', $msg);
                      $ok = false;
                    }
										break;
									case GeneralHelper::PAYPROVIDER_CRYPTOWALLET:
										try {
											$providerMode = Yii::$app->params['cryptowallet_mode'];
											

										} catch (\Exception $e) {
											$msg = '** actionPay 1 CryptoWallet ERROR: ' . $e->getMessage();
											Yii::trace($msg);
											$userpayModel->addError('_exception', $msg);
											$ok = false;
										}
										break;
									case GeneralHelper::PAYPROVIDER_NONE: $providerMode = 'none'; break;
								}
							}
						}
						if ($ok) {
							$prldata = Pricelist::find()->select(['prlsym_id', 'prl_maxsignals'])->where(['id'=>$prlid])->one();

							$userpayModel->upyumb_id = $id;
							$userpayModel->upymbr_id = $mbrid;
							$userpayModel->upyprl_id = $prlid; // was saved in umb for here or from discount
							$userpayModel->upy_state = Userpay::UPY_STATE_PAYING;
							$userpayModel->upy_name = Yii::$app->user->identity->username;
							$userpayModel->upy_maxsignals = $prldata->prl_maxsignals;
							$userpayModel->upy_percode = $percode;
							$userpayModel->upy_startdate = $usermemberModel->umb_paystartdate; //$startdate
							$userpayModel->upy_enddate = $usermemberModel->umb_payenddate; //$enddate;
							$userpayModel->upy_discountcode = $discountcode;
							$userpayModel->upy_payamount = $fiatprice;
							$userpayModel->upysym_pay_id = $prldata->prlsym_id;
							if ($cryptovalid) {
								$userpayModel->upycad_to_id = $cadid;
								$userpayModel->upy_cryptoamount = $cryptoprice;
								$userpayModel->upysym_crypto_id = $cadsymid;
								$userpayModel->upy_payreply = json_encode($payreply);
							}
							$userpayModel->upy_providermode = $providerMode;
							$userpayModel->upy_payprovider = $payProvider;
							$userpayModel->upy_providername = $_POST['Userpay']['upy_providername'];
							$userpayModel->upy_fromaccount = $_POST['Userpay']['upy_fromaccount'];
							$userpayModel->upy_toaccount = '';
							$userpayModel->upy_payref = $payReference;
							$userpayModel->upy_providerid = $providerid;
							$userpayModel->upy_createdat = $userpayModel->upy_updatedat = time();
							$userpayModel->upyusr_id = $userpayModel->upyusr_created_id = $userpayModel->upyusr_updated_id = $usrid;
							$userpayModel->upy_createdt = $userpayModel->upy_updatedt = date('Y-m-d H:i:s', time());
							$userpayModel->_acceptMembershipterms = $_POST['Userpay']['_acceptMembershipterms'];
							$ok = $userpayModel->save();
							Yii::trace('*** actionPay ok='.($ok?'OK':'NotOK'));
							if ($ok) {
								// temp set umbupy_id , so we can refind upy_payprovider when cancelled and re enter thsi screen..
								$sql = "UPDATE usermember SET umbupy_id=".$userpayModel->id." WHERE id=".$id;
								GeneralHelper::runSql($sql);
							}
						}

						if ($ok) {
						// post-save actions
							switch ($payProvider) {
								case GeneralHelper::PAYPROVIDER_PAYPAL:
									//$environmentPP = new SandboxEnvironment(Yii::$app->params['payPalClientId'], Yii::$app->params['payPalClientSecret']);
									//$client = new PayPalHttpClient($environmentPP);

							    // Setup order information array (keep this method)
									$params = [
										'method' => 'paypal',
										'intent' => 'sale',
										'returnUrl' => $returnUrl,
										'order' => [
											'invoicenumber' => $payReference,
											'description' => $$mbrTitle,
											'subtotal' => $fiatprice,
											'shippingCost' => 0,
											'total' => $fiatprice,
											'currency' => $fiatcode,
											'items' => [
												[
													'name' => $periodCodeText,
													'price' => $fiatprice,
													'quantity' => 1,
													'currency' => $fiatcode
												],
											]
										]
									];
									Yii::trace('** PAYPAL params: '.print_r($params,true));
									Yii::$app->PayPalRestApi->checkOut($params);
									Yii::$app->end(); // effectively end yii2 app here, giving paypal url time to react which returns via params returnurl
									break;

								case GeneralHelper::PAYPROVIDER_MOLLIE:
									try {
										$payment->getCheckoutUrl();
										header("Location: " . $payment->getCheckoutUrl(), true, 303);
										Yii::$app->end(); // effectively end yii2 app here, giving mollie url time to react which returns via params returnurl
									} catch (\Mollie\Api\Exceptions\ApiException $e) {
										$msg = '** actionPay 2 MOLLIE API call failed: ' . $e->getMessage();
										Yii::trace($msg);
										$userpayModel->addError('_exception', $msg);
										$ok = false;
									}
									break;

								case GeneralHelper::PAYPROVIDER_UTRUST:
									try {
										$utrustResponse = GeneralHelper::sendToUtrust('stores/orders', $utrustData);
										$msg='';
										if (!empty($utrustResponse['error'])) {
											$msg = 'Failed: ' . $utrustResponse['error'];
											$ok=false;
										} else if (!isset($utrustResponse['data']['attributes']['redirect_url'])) {
											$msg = 'Missing redirect url';
											$ok=false;
										}
										if (!$ok) {
											Yii::trace('** actionPay 2 UTRUST '.$msg);
											$userpayModel->addError($msg);
										} else {
											$redirectUrl = $utrustResponse['data']['attributes']['redirect_url'];
											Yii::trace('** actionPay 2 UTRUST Redirect Location => '.$redirectUrl);
											//header("Location: " . $redirectUrl, true, 303);
											header("refresh:1;url=".$redirectUrl);
											echo Yii::t('app', "You'll be redirected in about 1 second. If not, click <a href='".$redirecturl."'>here</a>.");
											Yii::$app->end();
										}
									} catch (\Mollie\Api\Exceptions\ApiException $e) {
										$msg = '** actionPay 2 MOLLIE API call failed: ' . $e->getMessage();
										Yii::trace($msg);
										$userpayModel->addError('_exception', $msg);
										$ok = false;
                  }
									break;

								case GeneralHelper::PAYPROVIDER_COINBASE: break;
								case GeneralHelper::PAYPROVIDER_MORALIS: break;
								case GeneralHelper::PAYPROVIDER_COINQVEST: //CRYPTODIRECT:
									// code for /checkout/hosted
									//Yii::trace('** actionPay 2 CryptoDirect Redirect valid='.($cryptovalid ? 'True':'False').' url='.$cqRedirUrl);
									//if ($cryptovalid && !empty($cqRedirUrl)) {
									//	return $this->redirect( $cqRedirUrl );
									//}
									// code for after commit..
									Yii::trace('** actionPay 2 CryptoDirect commit valid, data: '.print_r($cryptoData, true));
									return $this->render('paycrypto', [
										'cryptoData' => $cryptoData,
									]);
									break;
								case GeneralHelper::PAYPROVIDER_NONE:
									return $this->redirect( $returnUrl.'&success=true' ); // direct to paydone
									break;
								case GeneralHelper::PAYPROVIDER_OTHER: break;
							}
							//if ($ok) {
							//	return $this->redirect(['payok', 'id' => $userpayModel->id]);
							//} // else fallthrough..
	     			} elseif (!\Yii::$app->request->isPost) {
  	     			$userpayModel->load($_GET);
		  	   	}
					} else {
						Yii::trace('*** actionPay ERROR *** id='.$id.' over allowed times to buy '.$$mbrQuantity.' <= '.$allowedtimes);
						$userpayModel->addError('_exception', Yii::t('app', 'Not able to buy: you have reached the limit of {allowedtimes} subscription(s)', ['allowedtimes'=>$allowedtimes]));
					}
				} else {
					Yii::trace('*** actionPay ERROR *** loggid usrid='.(\Yii::$app->user->id).' != umbusr_id='.$usermemberModel->umbusr_id);
					throw new HttpException(404, 'The loggedin user '.(\Yii::$app->user->id).' != user '.$usermemberModel->umbusr_id.' subscribing.');
				}
			} else {
				Yii::trace('*** actionPay ERROR *** id='.$id);
				throw new HttpException(404, 'The requested usermember id='.$id.' or does not exist.');
			}
			Yii::trace('*** actionPay RENDER *** id='.$id);
   	} catch (\Exception $e) {
     	$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
     	$userpayModel->addError('_exception', $msg);
   	}
    return $this->render('pay', [
      'userpayModel' => $userpayModel,
      'usermemberModel' => $usermemberModel,
    ]); // this step
	}

  public function actionPaydone($ref='', $success='', $paymentId='', $token='', $PayerID='')
  {
		$userpayModel = null; //new userpay;
		$ok = false;
		Yii::trace('** actionPaydone ref='.$ref.' success='.$success.' paymentId='.$paymentId.' token='.$token.' PayerID='.$PayerID);
    if (!empty($ref) && (($userpayModel = Userpay::find()->where(['upy_payref'=>$ref, 'upy_deletedat'=>0])->one()) !== null)) {
			if ($userpayModel->upy_state == Userpay::UPY_STATE_PAYING) { // not already processed
				$fiatcode = strtoupper($userpayModel->upysymPay->sym_code);
				switch ($userpayModel->upy_payprovider) {
					case GeneralHelper::PAYPROVIDER_PAYPAL:
						// Setup order information array
						$params = [
							'order'=>[
								// 'description'=>'Payment description',
								'subtotal' => $userpayModel->upy_payamount,
								'shippingCost' => 0,
								'total' => $userpayModel->upy_payamount,
								'currency' => $fiatcode,
							]
						];
						Yii::trace('** Paydone params: '.print_r($params,true));
  	  			// In case of payment success this will return the payment object that contains all information about the order
    				// In case of failure it will return Null
   	  			$payment = Yii::$app->PayPalRestApi->processPayment($params);
						//Yii::trace('** Paydone: payment: '.print_r($payment,true));
						break;
					case GeneralHelper::PAYPROVIDER_MOLLIE: break;
					case GeneralHelper::PAYPROVIDER_MORALIS: break;
					case GeneralHelper::PAYPROVIDER_CRYPTODIRECT:
						//
						break;
				}

				Yii::trace('** actionPaydone state paying -> paid');
				$userpayModel->upy_state = ($success=='true' ? Userpay::UPY_STATE_PAID : Userpay::UPY_STATE_CANCEL);
				$userpayModel->upy_payreply = json_encode(['paymentId'=>$paymentId, 'token'=>$token, 'payerId'=>$payerID]);
				if ($success=='true') $userpayModel->upy_paiddt = date('Y-m-d H:i:s');
				$userpayModel->upyusr_updated_id = \Yii::$app->user->id;
				$userpayModel->upy_updatedat = time();
				$userpayModel->upy_updatedt = date('Y-m-d H:i:s', time());
				$userpayModel->_acceptMembershipterms = 1; //.. required local var and was accepted to pass save()
				if ($userpayModel->save()) { $ok = true; }

				if ($ok && ($success=='true')) {
					Yii::trace('** actionPaydone upy saved');
					$ok = false;
					$mbr_discordroles = $mbr_title = '';
					$usermemberModel = Usermember::findOne($userpayModel->upyumb_id);
					if ($usermemberModel != null) {
						//if (empty($usermemberModel->umb_startdate)) $usermemberModel->umb_startdate = $usermemberModel->umb_paystartdate;
						//$usermemberModel->umb_enddate = $usermemberModel->umb_payenddate; // new paid enddata, set at paymentcycle start (startdate already set at firsd payment)
						$mbr_discordroles = $usermemberModel->umbmbr->mbr_discordroles;
						$mbr_title = $usermemberModel->umbmbr->mbr_title;
						$usermemberModel->umb_active = 1; // become active
						// recalc umb_enddate with adding upy_percode time ???
						$usermemberModel->umbusr_updated_id = \Yii::$app->user->id;
						$usermemberModel->umb_updatedat = time();
						$usermemberModel->umb_updatedt = date('Y-m-d H:i:s', time());
						if ($usermemberModel->save()) { $ok = true; }
					}
					Yii::trace('** actionPaydone umbid='.$usermemberModel->id.' saved: '.($ok ? 'ok':'NOK'));

					if ($ok) {
						// Update user if needed: sitelevel, discordroles
						$userModel = User::findOne(\Yii::$app->user->id);
						$update = false;
						if ($userModel) {
							Yii::trace('** actionPaydone 1 user id='.\Yii::$app->user->id.':'.$userModel->username.' sitelevel='.$userModel->usr_sitelevel);
							if (in_array($userModel->usr_sitelevel, [User::USR_SITELEVEL_NONE, User::USR_SITELEVEL_GUEST, User::USR_SITELEVEL_USER ])) {
								YII::trace('** actionPaydone 2 user:'.$userModel->username.' sitelevel='.$userModel->usr_sitelevel);
								$userModel->usr_sitelevel = User::USR_SITELEVEL_MEMBER;
								$update = true;
							} else {
								Yii::trace('** actionPaydone: no userlevel update due level at least member level: '.$userModel->usr_sitelevel);
							}

							// process & update Discord data.. (get actual data)
							//$today = mktime(0);
							//$startDay = generalHelper::showDateTime($usermemberModel->umb_startdate,'unix');
							//$endDay = generalHelper::showDateTime($usermemberModel->umb_enddate,'unix');
							//Yii::trace('** actionPaydone discordProcess: today='.$today.' start='.$startDay.' end='.$endDay);
							$discordId = 0; $discordRoles = []; $newRolename = [];
							//if (($startDay <= $today) && ($endDay >= $today) ) {
								$discordData = json_decode( GeneralHelper::getDiscordMember( $userModel->usr_discordid, $userModel->usr_discordusername), true);
								Yii::trace('** actionPaydone discordData:'.print_r($discordData,true));
								if (!empty($discordData['username'])) {
									$discordId = $userModel->usr_discordid = $discordData['id'];
									$userModel->usr_discordusername = $discordData['username'];
									$userModel->usr_discordnick = $discordData['nick'];
									$userModel->usr_discordjoinedat = $discordData['joinedat'];
									// append rols from membership (not: and which are not disabled)
									$userDiscordRoles = (is_array($discordData['roles']) ? $discordData['roles'] : explode(',', $discordData['roles']));
									$discordRoles = GeneralHelper::getDiscordRoles();
									//$disabledRoles = array_keys( GeneralHelper::disabledDiscordRoles() );
									Yii::trace('** actionPaydone discord.roles='.$userDiscordRoles);
									Yii::trace('** actionPaydone discord.roles='.implode(', ',$userDiscordRoles).' mbr.roles='.$mbr_discordroles);
									foreach(explode(',', $mbr_discordroles) as $role) {
										if (!in_array($role, $userDiscordRoles) /*&& !in_array($role, $disabledRoles)*/) {
											$userDiscordRoles[] = $role;
											$newRolename[] = $discordRoles[ $role ];
										}
									}
									$userModel->usr_discordroles = implode(',', $userDiscordRoles);
									Yii::trace('** actionPaydone result.roles='.$userModel->usr_discordroles);
									$update = true;
								}
							//} else {
							//	Yii::trace('** actionPaydone: no discord update due today outside membership start..end dates: '.$usermemberModel->umb_startdate.' .. '.$usermemberModel->umb_enddate);
							//}
							if ($update) {
								$userModel->updated_at = time();
								$userModel->updated_by = \Yii::$app->user->id;
								$userModel->usr_updatedt = date('Y-m-d H:i:s', time());
								$userok = $userModel->save(false); // no validity check
								Yii::trace('** actionPaydone user saveresult='.($userok ? "OK":"NotOK" . print_r($userModel->errors, true)));

								$result = GeneralHelper::saveRolesToDiscordServer($discordId, $userDiscordRoles);
								Yii::trace('** actionPaydone saveRolesToDiscordServer result: '.print_r($result, true));

								$msg = (!empty($discordData['nick']) ? $discordData['username'] : (!empty($discordData['username']) ? $discordData['username'] : '?')) . ' kocht "'.$mbr_title.'" en kreeg de rol(len): "' . implode(', ', $newRolename) .'"' ;
								$result = GeneralHelper::sendMessageToDiscordCategory('discord_premiumlogs'/*'discord_cryptoyardsignals'*/, $msg);
								Yii::trace('** actionPaydone sendMessageToDiscordCategory msg="'.$msg.'" => result: '.print_r($result, true));
							}
						}
					} else {
						Yii::trace('** actionPaydone success='.$success.' find user ERROR: '.print_r($userModel->errors,true));
					}
				} else {
					 Yii::trace('** actionPaydone success='.$success.' save userpayModel ERROR: '.print_r($userpayModel->errors,true));
				}
			}
			return $this->render('paydone', ['userpayModel' => $userpayModel, ]);
		} else {
			throw new HttpException(404, Yii::t('app', 'Error with post payment processing. Please contact support.'));
		}
  }

}

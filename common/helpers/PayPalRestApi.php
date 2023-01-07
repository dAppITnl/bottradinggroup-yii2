<?php
/**
 * Created by PhpStorm.
 * User: mhmdbackershehadi
 * Updated: CasNWK
 * Date: 23oct21
 * Time: 14:17
 */
namespace common\helpers;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use yii\helpers\Url;
use Yii;

class PayPalRestApi
{
    private $apiContext;
    //public $redirectUrl;

    public function __construct()
    {
				$mode = strtolower(Yii::$app->params['payPalMode']);
				if ($mode == 'sandbox' || $mode == 'live') {
        	$apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
								Yii::$app->params['payPalClientId_'.$mode],
								Yii::$app->params['payPalClientSecret_'.$mode]
            )
        	);
					$apiContext->setConfig([
        		'mode' => $mode,
					]);
					$this->apiContext = $apiContext;
				}
    }

    public function checkOut($params){
			try {
				Yii::trace('** Paypal checkout params: '.print_r($params, true));
        $payer = new Payer();
        $payer->setPaymentMethod($params['method']);
        $orderList = [];
        foreach ($params['order']['items'] as $orderItem){
            $item = new Item();
            $item->setName($orderItem['name'])
                ->setCurrency($orderItem['currency'])
                ->setQuantity($orderItem['quantity'])
                ->setPrice($orderItem['price']);
            $orderList[]=$item;
        }

        $itemList = new ItemList();
        $itemList->setItems($orderList);

        $details = new Details();
        $details->setShipping($params['order']['shippingCost'])
            ->setSubtotal($params['order']['subtotal']);

        $amount = new Amount();
        $amount->setCurrency($params['order']['currency'])
            ->setTotal($params['order']['total'])
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($params['order']['description'])
            ->setInvoiceNumber(uniqid());

        $redirectUrl = Url::to([$this->redirectUrl],true);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($params['returnUrl'].'&success=true') //"$redirectUrl?success=true")
            ->setCancelUrl($params['returnUrl'].'&success=false'); //"$redirectUrl?success=false");

        $payment = new Payment();
        $payment->setIntent($params['intent'])
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
			} catch (Exception $ex) {
				Yii::trace('Paypal checkout ERROR!!');
			}
				//$payCreated = new Payment();

        try {
						//Yii::trace('** Paypal checkout payment: '.print_r($payment, true));
            /*$payCreated = */$payment->create($this->apiContext);
						//Yii::trace('** checkout result payment: '.print_r($payment,true));

						$approvalLink = $payment->getApprovalLink();
						Yii::trace('** checkOut getApprovalLink='.$approvalLink);
            \Yii::$app->controller->redirect($approvalLink); // do not return to caller(!)
        }
        catch (PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
            \Yii::$app->response->data = $ex->getData();

						Yii::trace('** Paypal checkout catch 1: '.print_r($ex->getData(), true));
        }
				catch (Exception $ex) {
					  \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
            \Yii::$app->response->data = $ex->getData();

						Yii::trace('** Paypal checkout catch 2: '.print_r($ex->getData(), true));
				}
    }

    public function processPayment($params)
		{
				Yii::trace('** processPayment: GET='.print_r(Yii::$app->request->get(),true));

        if (isset(Yii::$app->request->get()['success']) && Yii::$app->request->get()['success'] == 'true') {
            $paymentId = Yii::$app->request->get()['paymentId'];
            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId(Yii::$app->request->get()['PayerID']);

            $transaction = new Transaction();

            $amount = new Amount();

            $details = new Details();
            $details->setShipping($params['order']['shippingCost'])
                ->setSubtotal($params['order']['subtotal']);
            $amount->setCurrency($params['order']['currency']);
            $amount->setTotal($params['order']['total']);
            $amount->setDetails($details);

            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);

            try {
                $payment->execute($execution, $this->apiContext);

                try {
                    $payment = Payment::get($paymentId, $this->apiContext);
                } catch (\Exception $ex) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                    \Yii::$app->response->data = $ex->getData();
                }
            } catch (\Exception $ex) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                \Yii::$app->response->data = $ex->getData();
            }

						Yii::trace('** processPayment: result payment: '.print_r($payment,true));

            \Yii::$app->response->data = $payment;

        }

        return Null;
    }

}

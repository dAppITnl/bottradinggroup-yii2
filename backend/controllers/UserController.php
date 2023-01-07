<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\helpers\GeneralHelper;
use backend\models\User;
use backend\models\Usermember;
use backend\models\Userpay;
use backend\models\Userbot;
use backend\models\Botsignal;
use RestCord\DiscordClient;

/**
* This is the class for controller "UserController".
*/
class UserController extends \backend\controllers\base\UserController
{

 /**
   * @inheritdoc
   */
  public function behaviors()
  {
    $access = GeneralHelper::checkSiteAccess();
    Yii::trace('** behavior MembershipController: '.print_r($access, true));
    return ArrayHelper::merge( parent::behaviors(),[
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          /*[
            'allow' => true,
            'actions' => ['index', 'view', 'create', 'update', 'delete'],
            'roles' => ['BackendMembershipFull'],
          ],
          [
            'allow' => true,
            'actions' => ['index', 'view'],
            'roles' => ['BackendMembershipView'],
          ],
          [
            'allow' => true,
            'actions' => ['update', 'create', 'delete'],
            'roles' => ['BackendMembershipEdit'],
          ],*/
          [
            'allow' => ($access['backend'] == 'true'),
            'roles' => ['@'],  // Allow authenticated/loged in users
          ],
          // anybody else is denied
        ],
      ],
    ]);
  }

// ======================

	public function actionUserdetail($id=0) {
		$userModel = $usermembersData = $userpaysData = $userbotsData = [];

		try {
			if (!empty($id)) {
				$userModel = User::find()->select(['id','username'])->where(['id'=>$id])->one();

				$usermembersData = Usermember::getUsermembersOfUser($id, false, true, true);
				Yii::trace('** actionUserdetail usrid='.($id).' -> usermembersData: '.print_r($usermembersData, true));

				$umbids = implode(',', array_keys($usermembersData));
				if (!empty($umbids)) {
					$userpaysData = Userpay::getPaymentsOfUsermembers($umbids, true, true, true);
					Yii::trace('** actionUserdetail umbids='.$umbids.' => userpaysData: '.print_r($userpaysData, true));

					$userbotsData = Userbot::getUserbotsOfUsermembers($umbids);
					Yii::trace('** actionUserdetail umbids='.$umbids.' => userbotsData: '.print_r($userbotsData, true));
				}
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** actionUserdetail error msg='.$msg);
			$userModel->addError('_exception', $msg);
		}

		return $this->render('user-details', [
			'userModel' => $userModel,
			'usermembersData' => $usermembersData,
			'userpaysData' => $userpaysData,
			'userbotsData' => $userbotsData
		]);
	}

// -------

  public function actionAddbot($id=0)
  {
    $result = Userbot::_addbot([
      'umbid' => $id,
      'redirect_addbotsignal' => '/botsignal/addbotsignal',
      'redirect_overview' => '/user/userdetail',
      'render_form' => 'user-details-bot_form',
    ]);
    if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
    else return $this->render($result['render']['form'], $result['render']['data']);
  }

  public function actionUpdatebot($id)
  {
    $result = Userbot::_updatebot([
      'id' => $id,
      'redirect_ok' => /* '/bot/index' */ Url::previous(),
      'render_form' => 'user-details-bot_form',
    ]);
    if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
    else return $this->render($result['render']['form'], $result['render']['data']);
  }

  public function actionDeletebot($id)
  {
    if (!empty($id) && is_numeric($id)) {
      $sql = "UPDATE userbot SET ubt_deletedat=".time().", ubt_deletedt=NOW(), ubtusr_deleted_id=". \Yii::$app->user->id ." WHERE id=".$id;
      $result = GeneralHelper::runSql($sql);
      Yii::trace('** actionDeletebot id='.$id.' result='.$result);
    }
    return $this->redirect( Url::previous() /*['/membership/index']*/ );
  }

// -------

  public function actionAddbotsignal($id)
  {
		$result = BotSignal::_addbotsignal([
			'ubtid' => $id,
			'redirect_ok' => Url::previous(),
			'render_form' => 'user-details-botsignal_form',
		]);
		if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
		else return $this->render($result['render']['form'], $result['render']['data']);
  }

  public function actionUpdatebotsignal($id)
  {
    $result = Botsignal::_updatebotsignal([
      'id' => $id,
      'redirect_ok' => Url::previous(),
      'render_form' => 'user-details-botsignal_form',
    ]);
    if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
    else return $this->render($result['render']['form'], $result['render']['data']);
  }

  public function actionDelbotsignal($id=0)
  {
    if (!empty($id) && is_numeric($id)) {
      $sql = "UPDATE botsignal SET bsg_deletedat=".time().", bsg_deletedt=NOW(), bsgusr_deleted_id=". \Yii::$app->user->id ." WHERE id=".$id;
      $result = GeneralHelper::runSql($sql);
      Yii::trace('** actionDelbotsignal id='.$id.' result='.$result);
    }
    return $this->redirect( Url::previous() );
  }

// ======================

  /**
  * Updates an existing User model with 2FA data.
  * If update is successful, the browser will be redirected to the 'view' page.
  * @param integer $id
  * @return mixed
  */
  public function actionCreate2fa($id)
  {
    try {
      $model = $this->findModel($id);
			$google2fa = new \PragmaRX\Google2FAQRCode\Google2FA();
      $ok = false;

			if (!empty($model->email)) {
				if (!empty($_POST['User'])) {
					Yii::trace('** Create2fa id='.$id.' POST:'.print_r($_POST,true));
					if (! $google2fa->verifyKey($model->usr_2fasecret, $_POST['User']['_authenticatorreply'])) {
						$model->addError('_exception', Yii::t('app', 'Please enter a correct 2FA authenticator code'));
					} else {
						return $this->redirect(Url::previous());
					}
				} else {
      		$model->usr_2fasecret = $google2fa->generateSecretKey();
        	$model->updated_at = time();
        	$model->updated_by = \Yii::$app->user->id;
        	$model->usr_updatedt = date('Y-m-d H:i:s', time());
					if ($model->save()) { $ok = true; }
				}
			} else {
				$model->addError('_exception', Yii::t('app', 'To use 2FA, you need to fill in your email address'));
			}

			$model->_inlineurl2fa = ($ok ? $google2fa->getQRCodeInline(Yii::$app->params['companyNameFor2FA'], $model->email, $model->usr_2fasecret) : '');
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $model->addError('_exception', $msg);
    }
    return $this->render('create2fa', [
      'model' => $model,
    ]);
  }



	public function actionGetdiscordmember()
	{
		$result = [];
		if (!empty($_POST['id']) || !empty($_POST['username'])) {
			$result = GeneralHelper::getDiscordMember( $_POST['id'], $_POST['username'] );
		}
		return $result;
	}


  public function actionSetdiscordtoken($id=0)
  {
    $userModel = new User;

		try {
			if (!empty($id)) {
				$userModel = User::findOne($id);

				Yii::trace('** actionSetdiscordtoken POST: '.print_r($_POST,true));
			  if (!empty($_POST) && $userModel->load($_POST)) {
					$discordId = $userModel->usr_discordid;
					$discordRoles = $userModel->usr_discordroles;

					if (is_array($discordRoles)) $userModel->usr_discordroles = implode(',', $discordRoles); // to string
					$userModel->updated_at = time();
					$userModel->updated_by = \Yii::$app->user->id;
					$userModel->usr_updatedt = date('Y-m-d H:i:s', time());

					if ($userModel->save()) {
						$result = GeneralHelper::saveRolesToDiscordServer($discordId, $discordRoles);
						Yii::trace('** actionSetdiscordtoken saveRolesToDiscordServer result: '.print_r($result, true));
					}
				}
				if (!is_array($userModel->usr_discordroles)) $userModel->usr_discordroles = explode(',', $userModel->usr_discordroles); // array
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** actionSetdiscordtoken error msg='.$msg);
			$userModel->addError('_exception', $msg);
    }
		//Yii::trace('** actionSetdiscordtoken user: '.print_r($userModel, true));
    return $this->render('setdiscordtoken', [
      'userModel' => $userModel,
    ]);
  }

// ================

	public function actionUserdetailmoralis($id) {
    $userModel = [];

		try {
			if (!empty($id)) {
				$userModel = User::find()->select(['id','username','usr_moralisid'])->where(['id'=>$id])->one();
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** actionUserdetailmoralis error msg='.$msg);
			$userModel->addError('_exception', $msg);
		}

		return $this->render('user-details-moralis', [
			'userModel' => $userModel,
		]);
	}

}

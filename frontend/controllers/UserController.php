<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\User;
use common\models\User as UserIdentify;
use frontend\models\SignupForm;
use yii\data\ActiveDataProvider;
use common\helpers\GeneralHelper;

/**
 * User controller
 */
class UserController extends Controller
{
	/**
   * {@inheritdoc}
   */
  public function behaviors()
  {
		$access = GeneralHelper::checkSiteAccess();
		Yii::trace('** behavior BotsignalController: '.print_r($access, true));
    return [
      'access' => [
        'class' => AccessControl::className(),
        //'only' => ['logout', 'signup'],
        'rules' => [
          /*[
            'actions' => ['signup'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],*/
          [
            'allow' => ($access['frontend'] == 'true'),
            'roles' => ['@'],  // Allow authenticated/loged in users
          ],
          // anybody else is denied
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      /*'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],*/
    ];
  }

  /**
   * Displays homepage.
   *
   * @return mixed
   */
  public function actionIndex()
  {
		return $this->render('index', []);
  }

	private function _getDiscordRoleNames($roles)
	{
		$discordRoles = GeneralHelper::getDiscordRoles();
		$result = [];
		foreach(explode(',', $roles) as $role) $result[] = $discordRoles[ $role ];
		asort($result, SORT_STRING | SORT_FLAG_CASE);
		return implode(",\n", $result);
	}

  /**
  * Updates an existing User model.
  * If update is successful, the browser will be redirected to the 'view' page.
  * @param integer $id
  * @return mixed
  */
  public function actionUpdate($id=0)
  {
		$userModel = new User;
    try {
      $userModel = User::findOne(!empty($id) ? $id : Yii::$app->user->id);
      $ok = $emailChanged = false;
			$prevEmail = $userModel->email;

			Yii::trace('** update POST:'.print_r($_POST, true));
      if ($userModel->load($_POST)) {
				$emailChanged = (/*!empty($_POST['']) &&*/  ($userModel->email != $prevEmail));
				if ($emailChanged) {
					$userModel->status = UserIdentify::STATUS_INACTIVE;
					$userModel->verification_token = UserIdentify::generateEmailVerificationToken(true);
				}
        $userModel->updated_at = time();
        $userModel->updated_by = \Yii::$app->user->id;
        $userModel->usr_updatedt = date('Y-m-d H:i:s', time());
        if ($userModel->save() && (!$emailChanged || SignupForm::sendEmail($userModel))) { $ok = true; }
      }
      if ($ok) {
				if ($emailChanged) Yii::$app->session->setFlash('success', Yii::t('app', 'Please check your inbox for verification email.'));
        return $this->redirect('/user/index'/*Url::previous()*/);
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $userModel->addError('_exception', $msg);
    }

		$discordRoles = self::_getDiscordRoleNames( $userModel->usr_discordroles );
		Yii::trace('** actionUpdate discordRoles='.$discordRoles);
		return $this->render('update', [
			'userModel' => $userModel,
			'discordRoles' => $discordRoles,
		]);
  }

  public function actionGetdiscordmember()
  {
    $result = [];
    if (!empty($_POST['uid']) && (!empty($_POST['did']) || !empty($_POST['username']))) { // did is DiscordID
			// get current roles from Discord
      $data = GeneralHelper::getDiscordMember($_POST['did'], $_POST['username'] );
			$result = json_decode($data, true);
			Yii::trace('** actionGetdiscordmember 1 result: '.print_r($result, true));
			// Add roles from current active membership(s)
			if ($_POST['uid'] == \Yii::$app->user->id) {
				$membershipDiscordroles = GeneralHelper::getMembershipDiscordroles4User($_POST['uid']);
				foreach($membershipDiscordroles as $nr => $role) {
					if (!in_array($role, $result['roles']) /*&& !in_array($role, $disabledRoles)*/) {
						$result['roles'][] = $role;
					}
				}
			}
			Yii::trace('** actionGetdiscordmember 2 result: '.print_r($result, true));
			$result['rolenames'] = self::_getDiscordRoleNames( implode(',',$result['roles']) );
			Yii::trace('** actionGetdiscordmember 3 result: '.print_r($result,true));
			Yii::trace('** actionGetdiscordmember 3 roles: '.$result->roles);
    }
    return json_encode($result);
  }

}

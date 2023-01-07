<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use yii\data\ActiveDataProvider;
use frontend\models\Userbot;
use frontend\models\Usermember;
use frontend\models\Botsignal;
use frontend\models\Signal;
use common\helpers\GeneralHelper;

//$signals = GeneralHelper::getCategorySignalsForUserBotsignal(Yii::$app->user->identity->usr_language);

/**
 * Bot controller
 */
class BotController extends Controller
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
          [
            'actions' => ['updatebotsignal'],
            'allow' => true,
            //'roles' => ['?'],
          ],
          /*[
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
		$userbotsModel = Userbot::getUserbotsOfUser(\Yii::$app->user->id, false);
    return $this->render('index', ['userbotsModel' => $userbotsModel]);
  }

  /**
   * Displays assigned bots.
   *
   * @return mixed
   */
  public function actionAssigned()
  {
    return $this->render('assigned', []);
  }

// ----------------------

  public function actionAddbot($id=0)
  {
		$result = Userbot::_addbot([
			'umbid' => $id,
			'redirect_addbotsignal' => '/bot/addbotsignal',
			'redirect_overview' => '/membership/index',
			'render_form' => 'bot_form',
		]);
		if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
		else return $this->render($result['render']['form'], $result['render']['data']);
	}

  public function actionUpdatebot($id)
  {
		$result = Userbot::_updatebot([
			'id' => $id,
			'redirect_ok' => /* '/bot/index' */ Url::previous(),
			'render_form' => 'bot_form',
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
		return $this->redirect( /*url::previous()*/ ['/membership/index'] );
	}

// ----------------

  public function actionAddbotsignal($id)
  {
		$result = BotSignal::_addbotsignal([
			'ubtid' => $id,
			'redirect_ok' => /* ['/', 'id' => $userbotModel->ubtumb_id] */ Url::previous(),
			'render_form' => 'botsignal_form',
		]);
		if (!empty($result['redirect'])) return $this->redirect($result['redirect']);
		else return $this->render($result['render']['form'], $result['render']['data']);
	}

	public function actionUpdatebotsignal($id)
	{
		$result = BotSignal::_updatebotsignal([
			'id' => $id,
			'redirect_ok' => '/bot/updatebot', // Url::previous()),
			'render_form' => 'botsignal_form',
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

// -------------

	public function actionUpdatesignalactive(/*$id, $checked*/)
	{
		$ok = false;
		try {
			if (!empty($_POST) && !empty($_POST['id']) && (($botsignalModel=Botsignal::findOne($_POST['id'])) !== null)) {
				Yii::trace('** actionUpdatesignalactive post:'.print_r($_POST,true));
				$botsignalModel->bsg_active = ((!empty($_POST['checked']) && ($_POST['checked']=='true')) ? 1 : 0);
				$botsignalModel->bsg_updatedat = time();
				$botsignalModel->bsgusr_updated_id = \Yii::$app->user->id;
				$botsignalModel->bsg_updatedt = date('Y-m-d H:i:s', time());
				if ($botsignalModel->save()) { $ok = true; }
				if (!$ok) $msg = $botsignalModel->getError();
			}
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** actionUpdatesignalactive ERROR ' . $msg);
		}
		$result = ['result' => ($ok ? 'Ok' : 'Error: '.$msg) ];
		Yii::trace('** actionUpdatesignalactive result='.print_r($result,true));
		return json_encode($result);
	}

// -----------------------

  public function actionGetsignaldescription($id=0)
  {
    $result = [];
    try {
      if (!empty($id)) {
        $signalDescription = Signal::findOne($id)->sig_description;
        if (!empty($signalDescription)) $result = ['description' => $signalDescription];
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $result= ['error' => $msg];
      Yii::trace('** actionGetsignaldescription ERROR ' . $msg);
    }
    Yii::trace('** actionGetsignaldescription result:'.print_r($result,true));
    return json_encode($result);
  }

}

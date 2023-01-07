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


  public function actionAddbot($id=0)
  {
		$usermemberModel = null;
		$userbotModel = new Userbot;
		$botsignalModel = null;
		$signalCounts = [];

		try {
			if (!empty($_POST) && (!empty($_POST['userbot']['ubtumb_id']))) $id = $_POST['userbot']['ubtumb_id'];
			if (!empty($id)) $usermemberModel = Usermember::findOne($id);
			$ubtid = 0;

			if (!empty($_POST) && ($usermemberModel !== null)) {
				$ok = false;
				if ($userbotModel->load($_POST)) {
					$json = json_decode($userbotModel->ubt_3cdealstartjson, true);
					if (!empty($json['bot_id']) && !empty($json['email_token'])) {
						$userbotModel->ubt_3cbotid = ''.$json['bot_id'];
						$userbotModel->ubtumb_id = $id;
						$userbotModel->ubtcat_id = 1;     // asumed 3Commas bot category
						//$userbotModel->ubt_active = 1;    // initially active
						$userbotModel->ubt_createdat = $userbotModel->ubt_updatedat = time();
						$userbotModel->ubtusr_created_id = $userbotModel->ubtusr_updated_id = \Yii::$app->user->id;
						$userbotModel->ubt_createdt = $userbotModel->ubt_updatedt = date('Y-m-d H:i:s', time());
						if ($userbotModel->save()) { $ok = true; $ubtid = $userbotModel->id; }
					} else {
						$userbotModel->addError('_exception', Yii::t('app', 'Invalid 3CDealStartJson: copy whole 3Commas "deal start signal".'));
					}
				}
				if ($ok) {
					$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $id );
					$maxSignals = 1 * $usermemberModel->umb_maxsignals;
					$usedSignals = 1 * $signalCounts[$id];

					if (($maxSignals == 0) || ($usedSignals < $maxSignals)) {
						return $this->redirect(['/bot/addbotsignal', 'id'=>$ubtid] ); // and add a signal..
					} else {
						return $this->redirect(['/membership/index'] ); // overview
					}
				} elseif (!\Yii::$app->request->isPost) {
					$userbotModel->load($_GET);
				}
			} else {
				$userbotModel->ubtumb_id = $id;
				$userbotModel->ubt_active = 1; // initial Active
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			$userbotModel->addError('_exception', $msg);
			Yii::trace('** actionAddbot ERROR ' . $msg);
		}
		$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $id );
		// if (!empty($ubtid)) $botsignalModel = BotSignal::find()->where(['bsgubt_id' => $ubtid])->all(); -- always no signals with new bot!

		Yii::trace('** actionAddbot id=umbid='.$id.' ubtid='.$ubtid.' count botsignal='.print_r($botsignalModel,true).' ');
		return $this->render('bot_form', [
			'usermemberModel' => $usermemberModel,
			'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'signalCounts' => $signalCounts,
		]);
	}

  public function actionUpdatebot($id)
  {
    $usermemberModel = null; //new Usermember;
    $userbotModel = null; // new Userbot;
		$botsignalModel = null; //new Botsignal;
		$signalCounts = [];

    try {
      if (!empty($id)
      && (($userbotModel=Userbot::findOne($id)) !== null)
      && (($usermemberModel=Usermember::findOne($userbotModel->ubtumb_id)) !== null)) {
        $ok = false;
        if (!empty($_POST)) {
          if ($userbotModel->load($_POST)) {
            $json = json_decode($userbotModel->ubt_3cdealstartjson, true);
            if (!empty($json['bot_id']) && !empty($json['email_token'])) {
              $userbotModel->ubt_3cbotid = ''.$json['bot_id'];
              $userbotModel->ubt_updatedat = time();
              $userbotModel->ubtusr_updated_id = \Yii::$app->user->id;
              $userbotModel->ubt_updatedt = date('Y-m-d H:i:s', time());
              if ($userbotModel->save()) { $ok = true; }
            } else {
              $userbotModel->addError('_exception', Yii::t('app', 'Invalid 3CDealStartJson: copy whole 3Commas "deal start signal".'));
            }
          }
          if ($ok) {
            return $this->redirect( /*['/bot/index']*/ Url::previous() );
          } elseif (!\Yii::$app->request->isPost) {
            $userbotModel->load($_GET);
          }
        }
				$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $userbotModel->ubtumb_id );
				$botsignalModel = Botsignal::find()->where(['bsgubt_id'=>$id, 'bsg_deletedat'=>0])->all();
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $userbotModel->addError('_exception', $msg);
      Yii::trace('** actionAddbot ERROR ' . $msg);
    }
    return $this->render('bot_form', [
      'usermemberModel' => $usermemberModel,
      'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'signalCounts' => $signalCounts,
    ]);
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

// -------------------------------------------

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

  public function actionAddbotsignal($id)
  {
    $userbotModel = null; //new Userbot;
    $botsignalModel = new Botsignal;

    try {
      if (!empty($id) && (($userbotModel = Userbot::findOne($id)) !== null)) {
        $botsignalModel->bsg_active = 1;    // initially active

        $ok = false;
        if (!empty($_POST)) {
          if ($botsignalModel->load($_POST)) {
            //$botsignalModel->bsgsig_ids = ((!empty($botsignalModel->bsgsig_ids) && is_array($botsignalModel->bsgsig_ids)) ? implode(',', $botsignalModel->bsgsig_ids) : '');
            $botsignalModel->bsgubt_id = $id;
            //$botsignalModel->bsg_active = 1;    // initially active
            //$botsignalModel->bsg_startdate = $userbotModel->ubtumb->umb_startdate;
            //$botsignalModel->bsg_enddate = $userbotModel->ubtumb->umb_enddate;
            $botsignalModel->bsg_createdat = $botsignalModel->bsg_updatedat = time();
            $botsignalModel->bsgusr_created_id = $botsignalModel->bsgusr_updated_id = \Yii::$app->user->id;
            $botsignalModel->bsg_createdt = $botsignalModel->bsg_updatedt = date('Y-m-d H:i:s', time());
            if ($botsignalModel->save()) { $ok = true; }
          }
          if ($ok) {
            return $this->redirect(/*['/', 'id' => $userbotModel->ubtumb_id]*/ Url::previous());
          } elseif (!\Yii::$app->request->isPost) {
            $botsignalModel->load($_GET);
          }
        }
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $botsignalModel->addError('_exception', $msg);
      Yii::trace('** actionAddusersignal ERROR ' . $msg);
    }
    return $this->render('botsignal_form', [
      'userbotModel' => $userbotModel,
      'botsignalModel' => $botsignalModel,
    ]);
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

  public function actionUpdatebotsignal($id)
  {
    $userbotModel = null; //new Userbot;
    $botsignalModel = null; //new Botsignal;

    try {
      if (!empty($id)
      && (($botsignalModel=Botsignal::findOne($id)) !== null)
      && (($userbotModel=Userbot::findOne($botsignalModel->bsgubt_id)) !== null)) {
        //$botsignalModel->bsgsig_ids = explode(',', $botsignalModel->bsgsig_ids);
        $ok = false;
        if (!empty($_POST)) {
          if ($botsignalModel->load($_POST)) {
            //$botsignalModel->bsgsig_ids = ((!empty($botsignalModel->bsgsig_ids) && is_array($botsignalModel->bsgsig_ids)) ? implode(',', $botsignalModel->bsgsig_ids) : '');
            $botsignalModel->bsg_updatedat = time();
            $botsignalModel->bsgusr_updated_id = \Yii::$app->user->id;
            $botsignalModel->bsg_updatedt = date('Y-m-d H:i:s', time());
            if ($botsignalModel->save()) { $ok = true; }
          }
          if ($ok) {
            return $this->redirect(/*['/', 'id' => $id]*/ Url::previous());
          } elseif (!\Yii::$app->request->isPost) {
            $botsignalModel->load($_GET);
          }
        }
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $botsignalModel->addError('_exception', $msg);
      Yii::trace('** actionAddbotsignal ERROR ' . $msg);
    }
    return $this->render('botsignal_form', [
      'userbotModel' => $userbotModel,
      'botsignalModel' => $botsignalModel,
    ]);
  }

}

<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\controllers\base;

use Yii;
use backend\models\Userbot;
use backend\models\UserbotSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use common\helpers\GeneralHelper;

/**
* UserbotController implements the CRUD actions for Userbot model.
*/
class UserbotController extends Controller
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
    Yii::trace('** behavior UserbotController: '.print_r($access, true));
    return [
    	'access' => [
    		'class' => AccessControl::className(),
    		'rules' => [
    			/*[
    				'allow' => true,
            'actions' => ['index', 'view', 'create', 'update', 'delete'],
            'roles' => ['BackendUserbotFull'],
          ],
    			[
    				'allow' => true,
            'actions' => ['index', 'view'],
            'roles' => ['BackendUserbotView'],
          ],
    			[
    				'allow' => true,
            'actions' => ['update', 'create', 'delete'],
            'roles' => ['BackendUserbotEdit'],
          ],*/
          [
            'allow' => ($access['backend'] == 'true'),
            'roles' => ['@'],  // Allow authenticated/loged in users
          ],
          // anybody else is denied
        ],
      ],
    ];
  }

	/**
	* Lists all Userbot models.
	* @return mixed
	*/
	public function actionIndex()
	{
    $searchModel  = new UserbotSearch;
    $dataProvider = $searchModel->search($_GET);

		Tabs::clearLocalStorage();

		Url::remember();
		\Yii::$app->session['__crudReturnUrl'] = null;

		return $this->render('index', [
			'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
		]);
	}

	/**
	* Displays a single Userbot model.
	* @param integer $id
	*
	* @return mixed
	*/
	public function actionView($id)
	{
		\Yii::$app->session['__crudReturnUrl'] = Url::previous();
		Url::remember();
		Tabs::rememberActiveState();

		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	* Creates a new Userbot model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return mixed
	*/
	public function actionCreate()
	{
    try {
      $model = new Userbot;
      $ok = false;
      if ($model->load($_POST)) {
        $model->ubt_createdat = $model->ubt_updatedat = time();
        $model->ubtusr_created_id = $model->ubtusr_updated_id = \Yii::$app->user->id;
        $model->ubt_createdt = $model->ubt_updatedt = date('Y-m-d H:i:s', time());
        if ($model->save()) { $ok = true; }
      }
      if ($ok) {
        return $this->redirect(['view', 'id' => $model->id]);
      } elseif (!\Yii::$app->request->isPost) {
        $model->load($_GET);
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $model->addError('_exception', $msg);
    }
    return $this->render('create', ['model' => $model]);
	}

	/**
	* Updates an existing Userbot model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id
	* @return mixed
	*/
	public function actionUpdate($id)
	{
    try {
      $model = $this->findModel($id);
      $ok = false;
      if ($model->load($_POST)) {
        $model->ubt_updatedat = time();
        $model->ubtusr_updated_id = \Yii::$app->user->id;
        $model->ubt_updatedt = date('Y-m-d H:i:s', time());
        if ($model->save()) { $ok = true; }
      }
      if ($ok) {
        return $this->redirect(Url::previous());
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $model->addError('_exception', $msg);
    }
		return $this->render('update', ['model' => $model, ]);
	}

	/**
	* Deletes an existing Userbot model.
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param integer $id
	* @return mixed
	*/
	public function actionDelete($id)
	{
    try {
      $model = $this->findModel($id); //->delete();
      $model->ubt_deletedat = time();
      $model->ubtusr_deleted_id = \Yii::$app->user->id;
      $model->ubt_deletedt = date('Y-m-d H:i:s', time());
      if ($model->save()) { $ok = true; }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      \Yii::$app->getSession()->addFlash('error', $msg);
      return $this->redirect(Url::previous());
    }

    // TODO: improve detection
    $isPivot = strstr('$id',',');
    if (!$ok && $isPivot) {
      return $this->redirect(Url::previous());
    } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
      Url::remember(null);
      $url = \Yii::$app->session['__crudReturnUrl'];
      \Yii::$app->session['__crudReturnUrl'] = null;

      return $this->redirect($url);
    } else {
      return $this->redirect(['index']);
    }
	}

	/**
	* Finds the Userbot model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return Userbot the loaded model
	* @throws HttpException if the model cannot be found
	*/
	protected function findModel($id)
	{
		if (($model = Userbot::findOne($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested userbot does not exist.');
		}
	}
}

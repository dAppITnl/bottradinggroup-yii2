<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\controllers\base;

use Yii;
use backend\models\Signal;
use backend\models\SignalSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use common\helpers\GeneralHelper;

/**
* SignalController implements the CRUD actions for Signal model.
*/
class SignalController extends Controller
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
    Yii::trace('** behavior SignalController: '.print_r($access, true));
    return [
    	'access' => [
    		'class' => AccessControl::className(),
    		'rules' => [
    			/*[
    				'allow' => true,
            'actions' => ['index', 'view', 'create', 'update', 'delete'],
            'roles' => ['BackendSignalFull'],
          ],
    			[
    				'allow' => true,
            'actions' => ['index', 'view'],
            'roles' => ['BackendSignalView'],
          ],
    			[
    				'allow' => true,
            'actions' => ['update', 'create', 'delete'],
            'roles' => ['BackendSignalEdit'],
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
	* Lists all Signal models.
	* @return mixed
	*/
	public function actionIndex()
	{
    $searchModel  = new SignalSearch;
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
	* Displays a single Signal model.
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
	* Creates a new Signal model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return mixed
	*/
	public function actionCreate()
	{
    try {
      $model = new Signal;
      $ok = false;
      if ($model->load($_POST)) {
        $model->sigcat_ids = ((!empty($model->sigcat_ids) && is_array($model->sigcat_ids)) ? implode(',', $model->sigcat_ids) : '');
				$model->sigmbr_ids = ((!empty($model->sigmbr_ids) && is_array($model->sigmbr_ids)) ? implode(',', $model->sigmbr_ids) : '');
        $model->sig_createdat = $model->sig_updatedat = time();
        $model->sigusr_created_id = $model->sigusr_updated_id = \Yii::$app->user->id;
        $model->sig_createdt = $model->sig_updatedt = date('Y-m-d H:i:s', time());
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
	* Updates an existing Signal model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id
	* @return mixed
	*/
	public function actionUpdate($id)
	{
    try {
      $model = $this->findModel($id);
			$model->sigcat_ids = explode(',', $model->sigcat_ids);
			$model->sigmbr_ids = explode(',', $model->sigmbr_ids);
      $ok = false;
      if ($model->load($_POST)) {
				$model->sigcat_ids = ((!empty($model->sigcat_ids) && is_array($model->sigcat_ids)) ? implode(',', $model->sigcat_ids) : '');
				$model->sigmbr_ids = ((!empty($model->sigmbr_ids) && is_array($model->sigmbr_ids)) ? implode(',', $model->sigmbr_ids) : '');
        $model->sig_updatedat = time();
        $model->sigusr_updated_id = \Yii::$app->user->id;
        $model->sig_updatedt = date('Y-m-d H:i:s', time());
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
	* Deletes an existing Signal model.
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param integer $id
	* @return mixed
	*/
	public function actionDelete($id)
	{
    try {
      $model = $this->findModel($id); //->delete();
      $model->sig_deletedat = time();
      $model->sigusr_deleted_id = \Yii::$app->user->id;
      $model->sig_deletedt = date('Y-m-d H:i:s', time());
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
	* Finds the Signal model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return Signal the loaded model
	* @throws HttpException if the model cannot be found
	*/
	protected function findModel($id)
	{
		if (($model = Signal::findOne($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested signal does not exist.');
		}
	}
}

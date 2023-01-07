<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\controllers\base;

use Yii;
use backend\models\Pricelist;
use backend\models\PricelistSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use common\helpers\GeneralHelper;

/**
* PricelistController implements the CRUD actions for Pricelist model.
*/
class PricelistController extends Controller
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
    Yii::trace('** behavior PricelistController: '.print_r($access, true));
    return [
      'access' => [
      	'class' => AccessControl::className(),
      	'rules' => [
        	/*[
    				'allow' => true,
          	'actions' => ['index', 'view', 'create', 'update', 'delete'],
          	'roles' => ['BackendPricelistFull'],
        	],
    			[
    				'allow' => true,
          	'actions' => ['index', 'view'],
          	'roles' => ['BackendPricelistView'],
        	],
    			[
    				'allow' => true,
          	'actions' => ['update', 'create', 'delete'],
          	'roles' => ['BackendPricelistEdit'],
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
	* Lists all Pricelist models.
	* @return mixed
	*/
	public function actionIndex()
	{
    $searchModel  = new PricelistSearch;
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
	* Displays a single Pricelist model.
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
	* Creates a new Pricelist model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	* @return mixed
	*/
	public function actionCreate()
	{
		$model = new Pricelist;
    try {
      $ok = false;
      if ($model->load($_POST)) {
				if (is_array($model->prlcad_crypto_ids)) $model->prlcad_crypto_ids = implode(',', $model->prlcad_crypto_ids); // to string
        $model->prl_createdat = $model->prl_updatedat = time();
        $model->prlusr_created_id = $model->prlusr_updated_id = \Yii::$app->user->id;
        $model->prl_createdt = $model->prl_updatedt = date('Y-m-d H:i:s', time());
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
		if (!is_array($model->prlcad_crypto_ids)) $model->prlcad_crypto_ids = explode(',', $model->prlcad_crypto_ids); // array
    return $this->render('create', ['model' => $model]);
	}

	/**
	* Updates an existing Pricelist model.
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
				if (is_array($model->prlcad_crypto_ids)) $model->prlcad_crypto_ids = implode(',', $model->prlcad_crypto_ids); // to string
        $model->prl_updatedat = time();
        $model->prlusr_updated_id = \Yii::$app->user->id;
        $model->prl_updatedt = date('Y-m-d H:i:s', time());
        if ($model->save()) { $ok = true; }
      }
      if ($ok) {
        return $this->redirect(Url::previous());
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $model->addError('_exception', $msg);
    }
		if (!is_array($model->prlcad_crypto_ids)) $model->prlcad_crypto_ids = explode(',', $model->prlcad_crypto_ids); // array
		return $this->render('update', ['model' => $model, ]);
	}

	/**
	* Deletes an existing Pricelist model.
	* If deletion is successful, the browser will be redirected to the 'index' page.
	* @param integer $id
	* @return mixed
	*/
	public function actionDelete($id)
	{
    try {
      $model = $this->findModel($id); //->delete();
      $model->prl_deletedat = time();
      $model->prlusr_deleted_id = \Yii::$app->user->id;
      $model->prl_deletedt = date('Y-m-d H:i:s', time());
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
	* Finds the Pricelist model based on its primary key value.
	* If the model is not found, a 404 HTTP exception will be thrown.
	* @param integer $id
	* @return Pricelist the loaded model
	* @throws HttpException if the model cannot be found
	*/
	protected function findModel($id)
	{
		if (($model = Pricelist::findOne($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested pricelist item does not exist.');
		}
	}
}

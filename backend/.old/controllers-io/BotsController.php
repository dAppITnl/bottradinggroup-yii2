<?php

namespace backend\controllers;

use Yii;
use backend\models\Bots;
use backend\models\BotsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BotsController implements the CRUD actions for Bots model.
 */
class BotsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bots models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BotsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bots model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
				$this->_view($model);
		}

		protected function _view($model) {
        $providerSignallogs = new \yii\data\ArrayDataProvider([
            'allModels' => $model->signallogs,
        ]);
        $providerUsersignals = new \yii\data\ArrayDataProvider([
            'allModels' => $model->usersignals,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerSignallogs' => $providerSignallogs,
            'providerUsersignals' => $providerUsersignals,
        ]);
    }

    /**
     * Creates a new Bots model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bots();
				$this->_create($model);
		}

		protected function _create($model)
		{
				$ok = false;
        if ($model->loadAll(Yii::$app->request->post())) {
						$now = time();
						$id = \Yii::$app->user->id;
						$model->created_at = $model->updated_at = $now;
						$model->created_by = $model->updated_by = $id;
						if ($model->saveAll()) $ok = true;
        }
    		if ($ok) {
						$this->_view($model);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bots model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Bots();
        }else{
            $model = $this->findModel($id);
        }

				$ok = false;
        if ($model->loadAll(Yii::$app->request->post())) {
            $now = time();
            $id = \Yii::$app->user->id;
            $model->updated_at = $now;
            $model->updated_by = $id;
					if ($model->saveAll()) $ok = true;
				}
				if ($ok) {
					$this->_view($model);
           //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bots model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();

        return $this->redirect(['index']);
    }
    
    /**
     * 
     * Export Bots information into PDF format.
     * @param integer $id
     * @return mixed
     */
    public function actionPdf($id) {
        $model = $this->findModel($id);
        $providerSignallogs = new \yii\data\ArrayDataProvider([
            'allModels' => $model->signallogs,
        ]);
        $providerUsersignals = new \yii\data\ArrayDataProvider([
            'allModels' => $model->usersignals,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerSignallogs' => $providerSignallogs,
            'providerUsersignals' => $providerUsersignals,
        ]);

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => \Yii::$app->name],
            'methods' => [
                'SetHeader' => [\Yii::$app->name],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    /**
    * Creates a new Bots model by another data,
    * so user don't need to input all field from scratch.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @param mixed $id
    * @return mixed
    */
    public function actionSaveAsNew($id) {
        $model = new Bots();

        if (Yii::$app->request->post('_asnew') != '1') {
            $model = $this->findModel($id);
        }

				$this_create($model);
    
        /*if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('saveAsNew', [
                'model' => $model,
            ]);
        }*/
    }
    
    /**
     * Finds the Bots model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bots the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bots::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested bot #'.$id.' does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Signallogs
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddSignallogs()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Signallogs');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formSignallogs', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Usersignals
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddUsersignals()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Usersignals');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formUsersignals', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested item does not exist.'));
        }
    }
}

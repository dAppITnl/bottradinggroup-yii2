<?php

namespace backend\controllers\api;

/**
* This is the class for REST controller "BotsignalController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class BotsignalController extends \yii\rest\ActiveController
{
	public $modelClass = 'backend\models\Botsignal';
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return ArrayHelper::merge(
    	parent::behaviors(),
    	[
    		'access' => [
    			'class' => AccessControl::className(),
    			'rules' => [
    				[
    					'allow' => true,
    					'matchCallback' => function ($rule, $action) {return \Yii::$app->user->can($this->module->id . '_' . $this->id . '_' . $action->id, ['route' => true]);},
    				]
    			]
    		]
    	]
    );
  }

	pubblic function actionTradingviewRx()
	{
		Yii::trace('** actionTradingviewRx GET: '.$_GET);
		Yii::trace('** actionTradingviewRx POST: '.print_r($_POST));
		if (!empty($_GET)) file_put_contents($path.'TV-GET-'.date('ymdHis').'.log', $_GET);
		if (!empty($_POST)) file_put_contents($path.'TV-POST-'.date('ymdHis').'.log', $_POST);
		
	}

}

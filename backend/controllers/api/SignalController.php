<?php

namespace backend\controllers\api;

/**
* This is the class for REST controller "SignalController".
*/

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SignalController extends \yii\rest\ActiveController
{
public $modelClass = 'backend\models\Signal';
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
}

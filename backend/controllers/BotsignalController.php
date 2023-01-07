<?php

namespace backend\controllers;

use Yii;
use backend\models\Signal;

/**
* This is the class for controller "BotsignalController".
*/
class BotsignalController extends \backend\controllers\base\BotsignalController
{

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

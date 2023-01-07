<?php

namespace backend\controllers;

use backend\models\Signallog;
use backend\models\Usermember;

/**
* This is the class for controller "SignallogController".
*/
class SignallogController extends \backend\controllers\base\SignallogController
{

	public function actionBotdetail($id)
	{
		$logdetail = [];
		try {
			if (!empty($id)) {
				$usermember = Usermember::getUsermemberFromBotsignal($id);
				$logdetails = Signallog::getBotdetails($id);
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			$logdetails['error'] = 'Error '.$msg;
			Yii::trace('** actionBotdetail ERROR ' . $msg);
		}

    return $this->render('logdetail', [
			'usermember' => $usermember,
      'logdetails' => $logdetails,
    ]);
	}

}

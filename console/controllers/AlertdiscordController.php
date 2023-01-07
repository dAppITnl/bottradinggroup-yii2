<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
//use yii\helpers\ArrayHelper;
//use apiv1\models\Signal;
//use apiv1\models\Botsignal;
//use apiv1\models\Userbot;
//use apiv1\models\Signallog;
use common\helpers\GeneralHelper;

class AlertdiscordController extends Controller
{

  public function actionSenddiscordmessages()
  {
		try {
			$sql = "SELECT id, slg_discordlogchanid, slg_discordlogmessage, slg_discordtologat"
						." FROM signallog"
						." WHERE (slg_discordtologat between '2022-02-15 21:00:00' and '".date('Y-m-d H:i:s')."') AND ((slg_discordlogdelaydone = '') OR (slg_discordlogdelaydone IS NULL))"
						." AND (slg_discordlogchanid != '') AND (slg_discordlogmessage != '')";
			$rows = GeneralHelper::runSql($sql);
			Yii::trace('** Senddiscordmessages sql='.$sql.' rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				Yii::trace('** Senddiscordmessages nr='.$nr.' row: '.print_r($row, true));
				$result = GeneralHelper::sendMessageToDiscordCategory($row['slg_discordlogchanid'], $row['slg_discordlogmessage']);
				$discordLogDone = date('ymd-His') .'='. (empty($result) ? "ok" : json_encode($result));
				Yii::trace('** Senddiscordmessages nr='.$nr.' => discordLogDone'.$discordLogDone);

				$sql = "UPDATE signallog SET slg_discordlogdelaydone='".$discordLogDone."', slg_updatedat='".time()."', slg_updatedt='".date('Y-m-d H:i:s')."'"
							." WHERE id=".$row['id'];
				$result = GeneralHelper::runSql($sql);
				Yii::error('** Senddiscordmessages update signallog: ['.$sql.'] => result: '.print_r($result, true));
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::error('** Senddiscordmessages => '.$msg);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}

}

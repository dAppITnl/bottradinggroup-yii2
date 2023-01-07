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
			$sql = "SELECT slg.id as slgid, slg_createdt, sig.sig_discordlogchanid, sig.sig_discordmessage, slg.slg_discordtologat, slg.slg_alertmsg"
						." FROM (signallog slg, `signal` sig)"
						." WHERE (slg_discordtologat > '2022-02-07 21:00:00') AND  (slg_discordtologat < NOW()) AND (slg_discordlogdone = 0)"
						." AND sig.id=slg.slgsig_id"
						." AND sig.sig_discordlogchanid IS NOT NULL";
			$rows = GeneralHelper::runSql($sql);
			//Yii::trace('** Senddiscordmessages rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				//Yii::trace('** Senddiscordmessages nr='.$nr.' row: '.print_r($row, true));
				$alertmsg = json_decode($row['slg_alertmsg']);
				$pair = (!empty($alertmsg['pair']) ? Yii::t('app', ' for ') . strtoupper($alertmsg['pair']) : '');
				$msg = $row['sig_discordmessage'];
				if (strpos($msg, '{pair}')    !== false) $msg = str_replace('{pair}', $pair, $msg);
				if (strpos($msg, '{logtime}') !== false) $msg = str_replace('{logtime}', GeneralHelper::showDateTime($row['slg_createdt'],'dmyhi'), $msg);

				Yii::trace('** Senddiscordmessages DiscordID='.$row['sig_discordlogchanid'].' => '.$msg);

				if (is_numeric($row['sig_discordlogchanid'])) {
					//Yii::trace('** AlertdiscordController index DiscordID='.$row['sig_discordlogchanid'].' => '.$msg);
					$result = GeneralHelper::sendMessageToDiscordCategory( $row['sig_discordlogchanid'], $msg);
					if (empty($result['error'])) {
						$sql = "UPDATE signallog SET slg_discordlogdone=1, slg_updatedat='".time()."', slg_updatedt='".date('Y-m-d H:i:s')."'"
									." WHERE id=".$row['slgid'];
						$result = GeneralHelper::runSql($sql);
					} else {
						Yii::error('** Senddiscordmessages update signallog: ['.$sql.'] => '.$result['error']);
					}
				}
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::error('** Senddiscordmessages => '.$msg);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}

}

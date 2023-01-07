<?php

namespace apiv1\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use apiv1\models\Signal;
use apiv1\models\Botsignal;
use apiv1\models\Userbot;
use apiv1\models\Userpay;
use apiv1\models\Signallog;
use common\helpers\GeneralHelper;

class To3cController extends Controller
{

	public function actionIndex()
	{
		//Yii::trace('** index GET: '.print_r($_GET,true));
		//Yii::trace('** index POST: '.print_r($_POST,true));
		$alertmsg = \Yii::$app->getRequest()->getRawBody();
		$alertmsg = str_replace(["\n","\r"], "\r", $alertmsg);
		Yii::trace('** index alertmsg: '. $alertmsg); // $GLOBALS['HTTP_RAW_POST_DATA']);

    $pairs = GeneralHelper::getSymbolCodeToPairs();
    Yii::trace('** index pairs: '.print_r($pairs, true));

		$actions = Signal::getSig3cActionTextsToSend();

		$lines = explode("\r", $alertmsg);
		$data = [];
		$ok = true; $errmsg = '';
		foreach($lines as $line) {
			list($key, $value) = explode(' ', $line, 2);
			$key = preg_replace("/[^a-zA-Z]/", "", $key);
			//Yii::trace('** index key='.$key.' value='.$value);
			switch (strtolower($key)) {
				case 'signal': $data['signal'] = $value; break;
				case 'pair':
					$pair = strtoupper($value);
					if (!empty($pairs[ $pair ])) $pair = $pairs[ $pair ];
					elseif (!empty($pair) && (strpos($pair, '_') === false)) {
						if     (str_ends_with($pair, 'BTC'))  $pair = substr_replace($pair, '_BTC', -3);
						elseif (str_ends_with($pair, 'USDT')) $pair = substr_replace($pair, '_USDT', -4);
						elseif (str_ends_with($pair, 'BUSD')) $pair = substr_replace($pair, '_BUSD', -4);
						//elseif (str_ends_with($pair, '')) $pair = substr_replace($pair, '_', -3);
						else { $ok = false; Yii::trace('Base (BTC, USDT, BUSD)not found in pair='.$pair); break 2; }
					}
					// reverse base and quote for 3C..
					list($quote, $base) = explode('_', $pair, 2);
					$data['pair'] = $base.'_'.$quote;
					break;
				case 'action':
					$value = strtolower($value);
					$actionValues = array_merge(array_values($actions), [
						//'signal_run_enabled', 'signal_run_disabled',
						'bot_signal_start', 'bot_signal_stop',
						//'start_all_bots', 'stop_all_bots'
					]);
					if (in_array($value, $actionValues)) //only known..
					{
						$data['action'] = $value;
					}
					break;
			}
		}
		Yii::trace('** index ok='.($ok?'OK':'NotOK').' data: '.print_r($data, true));

// ==========

		if ($ok) {
			// Enable / Disable sending signals via signal table
			if (($data['action'] == 'signal_run_enabled') || ($data['action'] == 'signal_run_disabled')) {
				$ok=true;
				try {
					if (!empty($data['signal'])) {
						$runenabled = (($data['action'] == 'signal_run_enabled') ? 1 : 0);
						$where = '';
						if (strtolower($data['signal']) != 'all') {
							$or ='';
							foreach(explode(',',$data['signal']) as $signal) {
								if (substr($signal, -1, 1) == '*') {
									$where .= $or . '(LOWER(sig_code) like "'.strtolower(substr($signal,0,-1)).'%" )';
								} else {
									$where .= $or . '(LOWER(sig_code)="'.strtolower($signal).'")';
								}
								$or=' OR ';
							}
						}
						$sql = "UPDATE `signal` SET sig_runenabled=".$runenabled.", sig_updatedt=NOW(), sig_updatedat=".time()
									." WHERE sig_deletedat=0" . (!empty($where) ? " AND (".$where.")" : "");
						$result = GeneralHelper::runSql($sql);
					} else {
						Yii::error('ERROR action='.$data['action'].' with no signal given, skipped!');
					}
				} catch (\Exception $e) {
					$ok=false;
					$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
					Yii::error('** index error save signal msg='.$msg);
				}

				$logstatus = ['ok' => 0, 'error' => 0 ];
				$logstatus[ ($ok ? 'ok' : 'error') ]++;

				$logsenddata/*[ $userbot['sig_id'] ]*/[] = [  // sig_id is same for these userbots
          'bsgid'    => 0,
          'senddata' => '',
          'message'  => '['.$data['action'].']: ' . ($ok ? $result.' records updated' : $msg)
        ];

				try {
					$signallog = new Signallog;
					$signallog->slgbsg_id = null;
					$signallog->slgsig_id = null;
					$signallog->slg_status = json_encode($logstatus);
					$signallog->slg_alertmsg = $alertmsg;
					$signallog->slg_senddata = json_encode($logsenddata);
					$signallog->slg_discordtologat = date("Y-m-d H:i:s", strtotime("+1 hours")); // delay with 1 hour
					$signallog->slg_discordlogdone = 0;
					//$signallog->slg_message = '['.$data['action'].']: ' . (is_array($result) ? print_r($result,true) : $result.' records updated');
					$signallog->slg_createdat     = $signallog->slg_updatedat = time();
					$signallog->slgusr_created_id = $signallog->slgusr_updated_id = 1; // SystemLogUser  ToDo ===> does not accept - query gives null value  == DB set to maybe NULL ..
					$signallog->slg_createdt      = $signallog->slg_updatedt = date('Y-m-d H:i:s', time());

					if (! ($saveResult=$signallog->save(false))) {
						$sigid = 0;
						Yii::error('** index error save 1 signallog: '.print_r($saveResult, true));
					} else {
						$sigid = $signallog->id;
					}
				} catch (\Exception $e) {
					$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
					Yii::error('** index error save 1 signallog msg='.$msg);
				}

				try {
					$sql = "SELECT id, sig_discordlogchanid, sig_discordmessage, sig_discorddelayminutes, "
								." FROM signal "
								." WHERE sig_discordlogchanid!='' AND   AND sig_deletedat=0";
					$rows = GeneralHelper::runSql($sql);
					if (empty($rows)) {
						
					}
				} catch (\Exception $e) {
					$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
					Yii::error('** index error send to discord 1 msg='.$msg);
        }

				try {
					$msg = '** SIGNAL RUN ' . ($runenabled==1 ? 'GESTART' : 'GESTOPT') . ': '.$data['signal'].' ** See log: https://adminbtg.bottradinggroup.nl/signallog/view?id='.$sigid;
					$result = GeneralHelper::sendMessageToDiscordCategory('discord_premiumlogs', $msg);
					Yii::trace('** index sendMessageToDiscordCategory 1 msg="'.$msg.'" => result TS='.$result['timestamp']); //print_r($result, true));
				} catch (\Exception $e) {
					$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
					Yii::error('** index error send to discord 2 msg='.$msg);
				}

// ----------

			} elseif (($data['action'] == 'bot_signal_start') || ($data['action'] == 'bot_signal_stop')) {
				// En- / Disable sending signals from userbot table
				$ok=true;
        try {
          $signalstart = (($data['action'] == 'bot_signal_start') ? 1 : 0);
          $sql = "UPDATE userbot SET ubt_signalstartstop=".$signalstart.", ubt_updatedt=NOW(), ubt_updatedat=".time()." WHERE ubt_deletedat=0";
          $result = GeneralHelper::runSql($sql);
        } catch (\Exception $e) {
					$ok=false;
          $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
          Yii::error('** index error save userbot msg='.$msg);
        }

				$logstatus = ['ok' => 0, 'error' => 0 ];
				$logstatus[ ($ok ? 'ok' : 'error') ]++;

				$logsenddata/*[ $userbot['sig_id'] ]*/[] = [  // sig_id is same for these userbots
					'bsgid'    => 0,
					'senddata' => '',
					'message'  => '['.$data['action'].']: ' . ($ok ? $result.' records updated' : $msg)
				];

        try {
          $signallog = new Signallog;
          $signallog->slgbsg_id = null;
          $signallog->slgsig_id = null;
          $signallog->slg_status = json_encode($logstatus);
          $signallog->slg_alertmsg = $alertmsg;
          $signallog->slg_senddata = json_encode($logsenddata);
          //$signallog->slg_message = '['.$data['action'].']: ' . (is_array($result) ? print_r($result,true) : $result.' records updated');
          $signallog->slg_createdat     = $signallog->slg_updatedat = time();
          $signallog->slgusr_created_id = $signallog->slgusr_updated_id = 1; // SystemLogUser  ToDo ===> does not accept - query gives null value  == DB set to maybe NULL ..
          $signallog->slg_createdt      = $signallog->slg_updatedt = date('Y-m-d H:i:s', time());

          if (! ($saveResult=$signallog->save(false))) {
            $sigid = 0;
            Yii::error('** index error save 2 signallog: '.print_r($saveResult, true));
          } else {
            $sigid = $signallog->id;
          }
        } catch (\Exception $e) {
          $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
          Yii::error('** index error save 2 signallog msg='.$msg);
        }

        try {
          $msg = ($signalstart==1 ? 'BTC neutral/uptrend [signals ON]' : 'BTC downtrend [signals OFF]') . '; zie log: https://adminbtg.bottradinggroup.nl/signallog/view?id='.$sigid;
          $result = GeneralHelper::sendMessageToDiscordCategory('discord_premiumlogs', $msg);
          Yii::trace('** index sendMessageToDiscordCategory 2 msg="'.$msg.'" => result TS='.$result['timestamp']); //print_r($result, true));
        } catch (\Exception $e) {
          $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
          Yii::error('** index error send to discord 2 msg='.$msg);
        }

// ---------

			} else {
				$signalStartStop = false && (($data['action'] == 'start_all_bots') || ($data['action'] == 'stop_all_bots')); // never !
				$stopall = ($data['action'] == 'stop_all_bots');
				if ($signalStartStop) {
					$sql = "SELECT DISTINCT ubt.id as id, ubt.ubt_3cdealstartjson, ubt.ubt_name, umb.umb_name, 0 as bsg_id, 0 as sig_id" // donot add upy fields -> doubles if user has double paid records..
								." FROM (userbot ubt, usermember umb, userpay upy)"
								." WHERE ubt.ubt_active=1 AND ubt.ubt_deletedat=0"
								."   AND ubt.ubtumb_id=umb.id AND umb.umb_active=1 AND umb.umb_deletedat=0"
								."   AND upy.upyumb_id=umb.id AND AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND (NOW() BETWEEN upy.upy_startdate and upy.upy_enddate)"
								." ORDER BY umb.umb_name, ubt.ubt_name, ubt.id";
				} else {
					$sql = "SELECT DISTINCT ubt.id as id, ubt.ubt_3cdealstartjson, bsg.id as bsg_id, sig.id as sig_id, sig.sig_code, sig.sig_3cactiontext, ubt.ubt_name, umb.umb_name"
								." FROM (`signal` sig, botsignal bsg, userbot ubt, usermember umb, userpay upy)"
								." WHERE LOWER(sig.sig_code)='".strtolower($data['signal'])."' AND sig.sig_runenabled=1 AND (sig.sig_active=1 OR sig.sig_active4admin=1) AND sig.sig_deletedat=0"
								."   AND bsg.bsgsig_id=sig.id AND bsg.bsg_active=1 AND bsg.bsg_deletedat=0"
								."   AND (ubt.ubt_userstartstop=0 || (ubt.ubt_userstartstop=1 AND ubt.ubt_signalstartstop=1))" // user enabled check for alert has enabled sending signal
								."   AND bsg.bsgubt_id=ubt.id AND ubt.ubt_active=1 AND ubt.ubt_deletedat=0"
								."   AND ubt.ubtumb_id=umb.id AND umb.umb_active=1 AND umb.umb_deletedat=0"
								."   AND upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND (NOW() BETWEEN upy.upy_startdate and upy.upy_enddate)"
								." ORDER BY umb.umb_name, ubt.ubt_name, ubt.id";
								// LOCATE(CONCAT(',',sig.id,','),CONCAT(',',bsg.bsgsig_ids,','))>0 AND NOW() BETWEEN bsg.bsg_startdate and bsg.bsg_enddate
				}
				Yii::trace('** index signalStartStop='.($signalStartStop?'true':'false').' stopall='.($stopall?'true':'false').' sql='.$sql);
				$userbots = GeneralHelper::runSql($sql);
				Yii::trace('** index userbots: '.print_r($userbots, true));

				$toUrl = Yii::$app->params['3commasTradingviewWebhook']; // no authentication needed + at 3commas bot set to "Manual/API"

				$sigid = 0;
				$logsenddata = [];
				$logstatus = ['ok' => 0, 'error' => 0 ];

				foreach($userbots as $ubtid => $userbot) {
					$ok = true; $msg='Ok';
					if (is_numeric($ubtid)) { // skip 'rowcount' item
						$sigid = $userbot['sig_id']; // for every userbot the same..
						Yii::trace('** ## index ubtid='.$ubtid.' userbot='.print_r($userbot,true));
						$jsonData = json_decode($userbot['ubt_3cdealstartjson'], true);
						Yii::trace('** ## index jsonData='.print_r($jsonData, true));

						if (empty($jsonData['message_type']) || ($jsonData['message_type'] != 'bot')) { $ok=false; $msg='Wrong message_type for ubtId='.$ubtid; }
						elseif (empty($jsonData['email_token'])) { $ok=false; $msg='No or empty email_token for ubtId='.$ubtid; }
						else {
							if ($signalStartStop) { $jsonData['action'] = ($stopall ? $action[ Signal::SIG_3CACTIONTEXT_STOPBOT ] : $action[ Signal::SIG_3CACTIONTEXT_STARTBOT ]); }
							elseif (!empty($data['action'])) { $jsonData['action'] = $data['action']; } // TV-alert action leading
							elseif (!empty($userbot['sig_3cactiontext'])) { $jsonData['action'] = $actions[ $userbot['sig_3cactiontext'] ]; if (empty($jsonData['action'])) { unset($jsonData['action']); /* the default startdeal one */} }
							else { unset($jsonData['action']); } // remove if from userbot (so user cannot define an action) defaults to 3C's (no action) 'deal_start'

							if (!empty($data['pair'])) $jsonData['pair'] = $data['pair'];

							$sendData = json_encode($jsonData, JSON_PRETTY_PRINT);
							Yii::trace('** ## index changed jsonData='.$sendData);
							$result = self::_sendData($toUrl, $sendData);
						}

						$logsenddata/*[ $userbot['sig_id'] ]*/[] = [  // sig_id is same for these userbots
							'bsgid'    => $userbot['bsg_id'],
							'senddata' => $sendData,
							'message'  => '['.$userbot['umb_name'].']->['.(!empty($userbot['ubt_name']) ? $userbot['ubt_name'] : $ubtid).']: '.(!empty($result) ? (is_array($result) ? print_r($result,true):$result) : "Ok")
						];
						$logstatus[ (empty($result) ? 'ok' : 'error') ]++;

						/*try {
							$signallog = new Signallog;
							$signallog->slgbsg_id = $userbot['bsg_id'];
							$signallog->slgsig_id = $userbot['sig_id'];
							$signallog->slg_status = (empty($result) ? Signallog::SLG_STATUS_OK : Signallog::SLG_STATUS_ERROR);
							$signallog->slg_alertmsg = $alertmsg;
							$signallog->slg_senddata = $sendData;
							$signallog->slg_message = '['.$userbot['umb_name'].']->['.(!empty($userbot['ubt_name']) ? $userbot['ubt_name'] : $ubtid).']: ' . (!empty($result) ? (is_array($result) ? print_r($result,true):$result) : "Ok");
							$signallog->slg_createdat     = $signallog->slg_updatedat = time();
        			$signallog->slgusr_created_id = $signallog->slgusr_updated_id = 1; // SystemLogUser  ToDo ===> does not accept - query gives null value  == DB set to maybe NULL ..
		        	$signallog->slg_createdt      = $signallog->slg_updatedt = date('Y-m-d H:i:s', time());

							if (! ($saveResult=$signallog->save(false))) {
								Yii::error('** index error save 3 signallog: '.print_r($saveResult, true));
							}
						} catch (\Exception $e) {
							$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
							Yii::error('** index error save 3 signallog msg='.$msg);
							break;
						}*/

						if (!empty($result)) {
							Yii::error('ERROR Sending to '.$toUrl.' data='.$data.' => '.(is_array($result) ? print_r($result,true) : $result));
							break;
						}
					}
				}
				try {
					$signallog = new Signallog;
					//$signallog->slgbsg_id = null; //$userbot['bsg_id'];
					$signallog->slgsig_id = $sigid; //$userbot['sig_id'];
					$signallog->slg_status = json_encode($logstatus);  //(empty($result) ? Signallog::SLG_STATUS_OK : Signallog::SLG_STATUS_ERROR);
					$signallog->slg_alertmsg = $alertmsg;
					$signallog->slg_senddata = json_encode($logsenddata);
					$signallog->slg_discordtologat = date("Y-m-d H:i:s", strtotime("+1 hours")); // delay with 1 hour
					$signallog->slg_discordlogdone = 0;
					//$signallog->slg_message = '';
					$signallog->slg_createdat     = $signallog->slg_updatedat = time();
					$signallog->slgusr_created_id = $signallog->slgusr_updated_id = 1; // SystemLogUser  ToDo ===> does not accept - query gives null value  == DB set to maybe NULL ..
					$signallog->slg_createdt      = $signallog->slg_updatedt = date('Y-m-d H:i:s', time());
					if (! ($saveResult=$signallog->save(false))) {
						Yii::error('** index error save 3 signallog: '.print_r($saveResult, true));
					}
				} catch (\Exception $e) {
					$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
					Yii::error('** index error save 3 signallog msg='.$msg);
				}

			}
		}
	}

// =========

	public function actionTime() {
		$toUrl = Yii::$app->params['3commasApiUrl'].'time';
		return self::_sendData($toUrl);
	}

	private function _sendData($url='', $postData='') {
		$result = '';
		Yii::trace('** sendData url='.$url.' postData='.$postData);
		if (!empty($url)) {
			if (!empty($postData)) {
				// use key 'http' even if you send the request to https://...
				$options = [
					'http' => [
						'header'  => "Content-type: text/plain\r\n",  //"Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => (is_array($postData) ? http_build_query($postData) : $postData)
					]
				];
				Yii::trace('** sendData options: '.print_r($options, true));
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
			} else {
				$result = file_get_contents($url);
			}
		}
		Yii::trace('** sendData result='.$result);
		return $result;
	}

}



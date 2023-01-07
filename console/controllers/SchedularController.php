<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
//use yii\helpers\ArrayHelper;
//use apiv1\models\Signal;
//use apiv1\models\Botsignal;
//use apiv1\models\Userbot;
//use apiv1\models\Signallog;
use common\models\User;
use console\models\Userpay;
use common\helpers\GeneralHelper;

/*
Run:

./yii schedular/updatediscordroles
./yii schedular/updatemaxsignals

*/


class SchedularController extends Controller
{
	const UPDATE_DISCORD_LOG    = '/usr/share/php/bottradinggroup-yii2/console/runtime/logs/Updatediscordroles.log';
	const UPDATE_MAXSIGNALS_LOG = '/usr/share/php/bottradinggroup-yii2/console/runtime/logs/Updatemaxsignals.log';
	const EMAIL_MAXSIGNALS_LOG  = '/usr/share/php/bottradinggroup-yii2/console/runtime/logs/Emailmaxsignals.log';

// usr.id=53 => usrdiscordroles=905148136291442808,885839738316128266,886300278838669382,892860718435684392
// mbrdiscordroles=892860718435684392

	private function _getDiscordMember($discordUserid) {
		$roles = [];
		if (!empty($discordUserid)) {
			$discorddata = json_decode( GeneralHelper::getDiscordMember($discordUserid, ''), true);
			//Yii::trace('** _getDiscordMember discorddata: '.print_r($discorddata, true));
			if (!empty($discorddata['roles'])) {
				$roles = (is_array($discorddata['roles']) ? $discorddata['roles'] : explode(',', $discorddata['roles']));
			} else if (!empty($discorddata['error'])) {
				$roles = ''.$discorddata['error'];
			}
		}
		return $roles;
	}

  public function actionUpdatediscordroles()
  {
		$origdiscord = $updatediscord = [];
		$alsoupdate = true;
		$updated = [];
		try {
			Yii::trace('** .1. Check for mbr_discordroles to be removed..');
			$sql = "SELECT usr.id as usrid, usr.usr_discordid, MAX(upy.upy_enddate) as maxenddate, IFNULL(usr.usr_discordroles,'') as usrdiscordroles, IFNULL(mbr.mbr_discordroles,'') as mbrdiscordroles, umb.id as umbid"
						." FROM (user usr, usermember umb, membership mbr, userpay upy)"
						." WHERE umb.umbusr_id=usr.id AND umb.umbmbr_id=mbr.id AND umb.umb_active=1 AND umb.umb_deletedat=0"
						." AND usr.usr_discordid!=0 AND usr.usr_discordroles!='' AND usr.status='".User::STATUS_ACTIVE."' AND usr.deleted_at=0"
						." AND mbr.mbr_discordroles>'' AND mbr.mbr_active=1 AND mbr.mbr_deletedat=0"
						." AND upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND upy.upy_deletedat=0"
						." GROUP BY upy.upyumb_id HAVING MAX(upy.upy_enddate) < DATE(NOW())";
 			$rows = GeneralHelper::runSql($sql);
			//Yii::trace('** Updatediscordroles-DEL rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				Yii::trace('** Updatediscordroles-DEL nr='.$nr.' row: '.print_r($row, true));
				// get current roles from discord and merge with table ones..
				$currentdiscordroles = [];
				$usrdiscordid = $row['usr_discordid'];
				if (!empty($usrdiscordid)) {
					$currentdiscordroles = self::_getDiscordMember($usrdiscordid);
					// save as current for later
					$currentdiscordrolesstr = implode(',', $currentdiscordroles);
					$origdiscord[ $usrdiscordid ] = $currentdiscordrolesstr; //implode(',', $usrdiscordroles);
					Yii::trace('** Updatediscordroles-DEL currentdiscordroles: '. $currentdiscordrolesstr);  //implode(',',$currentdiscordroles));
				}
				if (is_array($currentdiscordroles))	$usrdiscordroles = array_unique(array_merge($currentdiscordroles, explode(',', $row['usrdiscordroles'])), SORT_NUMERIC);
				// save as current for later
				//$origdiscord[ $row['usr_discordid'] ] = implode(',', $usrdiscordroles);
				// remove stopped membership ones
				$resultsql = $resultdiscord = '';
				if (!empty($row['mbrdiscordroles']) && !empty($row['usrdiscordroles'])) {
					Yii::trace('** Updatediscordroles-DEL 2b-removed: mbrroles='.$row['mbrdiscordroles']);
					Yii::trace('** Updatediscordroles-DEL existing roles='.implode(',',$usrdiscordroles));
					foreach(explode(',', $row['mbrdiscordroles']) as $discordrole) {
						if (($i=array_search($discordrole, $usrdiscordroles)) !== false) {
							Yii::trace('** Updatediscordroles-DEL i='.$i.' will remove: '.$usrdiscordroles[$i]);
							unset($usrdiscordroles[$i]);
						}
					}
				}
				// save new roles if not same as table ones merged with current discord ones
				$newroles = implode(',', $usrdiscordroles);
				Yii::trace('** Updatediscordroles-DEL save? newroles='.$newroles);
				if ($row['usrdiscordroles'] != $newroles) {
					$sql = "UPDATE user SET usr_discordroles='".$newroles."', updated_at='".time()."', usr_updatedt=NOW() WHERE id=".$row['usrid'];
					$resultsql = ($alsoupdate ? GeneralHelper::runSql($sql) : 'NOT UPDATED by: '.$sql);
					Yii::trace('** Updatediscordroles-DEL sql='.$sql.' => resultsql: '.$resultsql);
					$updated[] = date('YMd-His').': usrid='.$row['usrid'].' d-roles='.$newroles.' => '.$resultsql;
					// save changed roles for later to send to discord
					if (!empty($usrdiscordid)) {
						$updatediscord[ $usrdiscordid ] = $newroles;
						Yii::trace('** Updatediscordroles-DEL remember: updatediscord[ '.$usrdiscordid.' ] = '.$newroles);
					}
				}
			}

			Yii::trace('** Updatediscordroles 1 remember updatediscord: '.print_r($updatediscord, true));

			// Add roles
			Yii::trace('** .2. Check for mbr_discordroles to be added..');
			$sql = "SELECT usr.id as usrid, usr.usr_discordid, MAX(upy.upy_enddate) as maxenddate, IFNULL(usr.usr_discordroles,'') as usrdiscordroles, IFNULL(mbr.mbr_discordroles,'') as mbrdiscordroles, umb.id as umbid"
						." FROM (user usr, usermember umb, membership mbr, userpay upy)"
						." WHERE umb.umbusr_id=usr.id AND umb.umbmbr_id=mbr.id AND umb.umb_active=1 AND umb.umb_deletedat=0"
						." AND usr.usr_discordid!=0 AND usr.usr_discordroles!='' AND usr.status='".User::STATUS_ACTIVE."' AND usr.deleted_at=0"
						." AND mbr.mbr_discordroles>'' AND mbr.mbr_active=1 AND mbr.mbr_deletedat=0"
						." AND upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND upy.upy_deletedat=0"
						." GROUP BY upy.upyumb_id HAVING MAX(upy.upy_enddate) >= DATE(NOW())";
			$rows = GeneralHelper::runSql($sql);
			//Yii::trace('** Updatediscordroles-ADD rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				Yii::trace('** Updatediscordroles-ADD nr='.$nr.' row: '.print_r($row, true));
				// if not already retrieved from discord above.. do it and merge
				$usrdiscordid = $row['usr_discordid'];
				if (!empty($usrdiscordid) && empty($origdiscord[ $usrdiscordid ])) {
					$currentdiscordroles = self::_getDiscordMember($usrdiscordid);
					// save as current for later
					$currentdiscordrolesstr = implode(',', $currentdiscordroles);
					$origdiscord[ $usrdiscordid ] = $currentdiscordrolesstr; //implode(',', $usrdiscordroles);
					Yii::trace('** Updatediscordroles-ADD currentdiscordroles: ' . $currentdiscordrolesstr);
				}
				$usrdiscordroles = array_unique(array_merge($currentdiscordroles, explode(',', $row['usrdiscordroles'])), SORT_NUMERIC);
				$resultsql = $resultdiscord = '';
				if (!empty($row['mbrdiscordroles']) && !empty($row['usrdiscordroles'])) {
					Yii::trace('** Updatediscordroles-ADD mbrroles='.$row['mbrdiscordroles']);
					Yii::trace('** Updatediscordroles-ADD oldroles='.$row['usrdiscordroles']);
					foreach(explode(',', $row['mbrdiscordroles']) as $discordrole) {
						if ((array_search($discordrole, $usrdiscordroles)) === false) {
							Yii::trace('** Updatediscordroles-ADD append='.$discordrole);
							$usrdiscordroles[] = $discordrole;
						}
					}
				}
				$newroles = implode(',', $usrdiscordroles);
				Yii::trace('** Updatediscordroles-ADD newroles='.$newroles);
				if ($row['usrdiscordroles'] != $newroles) {
					$sql = "UPDATE user SET usr_discordroles='".$newroles."', updated_at='".time()."', usr_updatedt=NOW() WHERE id=".$row['usrid'];
					$resultsql = ($alsoupdate ? GeneralHelper::runSql($sql) : 'NOT UPDATED by: '.$sql);
					Yii::trace('** Updatediscordroles-ADD sql='.$sql.' => resultsql: '.$resultsql);
					$updated[] = date('YMd-His').': usrid='.$row['usrid'].' d-roles='.$newroles.' => '.$resultsql;
					if (!empty($usrdiscordid)) {
						$updatediscord[ $usrdiscordid ] = $newroles;
						Yii::trace('** Updatediscordroles-ADD append: updatediscord[ '.$usrdiscordid.' ] = '.$newroles);
					}
				}
			}

			Yii::trace('** Updatediscordroles 2 updatediscord: '.print_r($updatediscord, true));

			Yii::trace('** .3. update discord server users with roles..');
			if (!empty($updatediscord)) {
				foreach($updatediscord as $discordid => $discordroles) {
					if (!empty($origdiscord[$discordid])) {
						if ($origdiscord[$discordid] != $discordroles) {
							$resultdiscord = ($alsoupdate ? GeneralHelper::saveRolesToDiscordServer($discordid, $discordroles) : 'NO DISCORD UPDATE!');
							Yii::trace('** Updatediscordroles-SERVER id='.$discordid.' : '.$discordroles.' => resultdiscord: '.implode(', ', $resultdiscord));
							$updated[] = date('YMd-His').': to discord id='.$discordid.': roles='.$discordroles.' => '.json_encode($resultdiscord);
						} else {
							Yii::trace('** Updatediscordroles-SERVER id='.$discordid.' : '.$discordroles.' equal to orig => not send to discord');
						}
					} else {
						Yii::trace('** Updatediscordroles-SERVER id='.$discordid.' : NO origdiscord available => not send to discord!');
					}
				}
			}
			$updated[] = date('YMd-His').': ---';
			file_put_contents(self::UPDATE_DISCORD_LOG, implode("\n", $updated), FILE_APPEND);
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** Updatediscordroles ERROR => '.$msg);
			file_put_contents(self::UPDATE_DISCORD_LOG, date('YMd-His').': '.$msg."\n".date('YMd-His').": ---\n", FILE_APPEND);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}

	public function actionUpdatemaxsignals() {
		$alsoupdate = true;
		$updated = [];
		try {
			$sql = "SELECT upy.upyumb_id, upy.upy_enddate, upy.upy_maxsignals, count(DISTINCT ubt.id) as activesignals"
						." FROM (userpay upy, userbot ubt)"
						." WHERE ((DATE(NOW()) BETWEEN upy.upy_startdate and upy.upy_enddate) OR (DATE(NOW()) > upy.upy_enddate)) AND upy.upy_state='paid' AND upy.upy_deletedat=0 AND upy.upy_maxsignals>0"
						." AND ubt.ubtumb_id=upy.upyumb_id AND ubt.ubt_active=1 AND ubt.ubt_deletedat=0"
						." GROUP BY ubt.ubtumb_id HAVING (upy.upy_maxsignals < count(DISTINCT ubt.id))";
			$rows = GeneralHelper::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr) && !empty($row['upyumb_id'])) {
				Yii::trace('** actionUpdatemaxsignals row['.$nr.']: '.json_encode($row));
				$sql = "UPDATE userbot SET ubt_active=0, ubt_updatedat='".time()."', ubt_updatedt=NOW() WHERE ubtumb_id=".$row['upyumb_id'];
				$result = ($alsoupdate ? GeneralHelper::runSql($sql) : 'NO UPDATE of: '.$sql);
				Yii::trace('** actionUpdatemaxsignals row['.$nr.']: '.$result);
				$updated[] = date('YMd-His').': umbid='.$row['upyumb_id'].': ends='.$row['upy_enddate'].': bot inactivated due maxSignals='.$row['upy_maxsignals'].', activeBots='.$row['activesignals'];
			}
			$updated[] = date('YMd-His').": ---\n";
			file_put_contents(self::UPDATE_MAXSIGNALS_LOG, implode("\n", $updated), FILE_APPEND);
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** Updatemaxsignals ERROR => '.$msg);
			file_put_contents(self::UPDATE_MAXSIGNALS_LOG, date('YMd-His').': '.$msg."\n".date('YMd-His').": ---\n", FILE_APPEND);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}

	private function sendEmailUpdateMaxsignalsForUsermembers($umbids='') {
		$sended = [];
		try {
			if (!empty($umbids)) {
				$sql = "SELECT DISTINCT usr.id, usr.email, usr.username, mbr.mbr_title FROM (user usr, usermember umb, membership mbr)"
							." WHERE umb.id IN (".$umbids.") AND mbr.id=umb.umbmbr_id AND usr.id=umb.umbusr_id AND email>'' AND username>'' AND usr.status='".User::STATUS_ACTIVE."'";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) {
					$result = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailUpdateMaxsignals-html', 'text' => 'emailUpdateMaxsignals-text'],
                ['user' => $row]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($row['email'])
            ->setSubject(Yii::t('app', '{appName}: update of {membership}', ['appName' => Yii::$app->name, 'membership' => $row['mbr_title']]))
            ->send();
					$sended[] = date('YMd-His').': usrid='.$row['id'].'='.$row['username'].'='.$row['email'].': ';
				}
			}
			$sended[] = date('YMd-His').': ---';
			file_put_contents(self::EMAIL_MAXSIGNALS_LOG, implode("\n", $sended), FILE_APPEND);
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** sendEmailUpdateMaxsignalsForUsermembers ERROR => '.$msg);
      file_put_contents(self::EMAIL_MAXSIGNALS_LOG, date('YMd-His').': '.$msg."\n".date('YMd-His').": ---\n", FILE_APPEND);
      return Controller::EXIT_CODE_ERROR;
    }
    return Controller::EXIT_CODE_NORMAL;
  }

	public function actionMembershipEndsSoonMails()
	{
		try {
			$sql = "SELECT "
						." FROM "
						." WHERE ";
			$rows = GeneralHelper::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr)) {

			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** sendEmailUpdateMaxsignalsForUsermembers ERROR => '.$msg);
			file_put_contents(self::EMAIL_MAXSIGNALS_LOG, date('YMd-His').': '.$msg."\n".date('YMd-His').": ---\n", FILE_APPEND);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}


}

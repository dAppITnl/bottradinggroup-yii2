<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
//use yii\helpers\ArrayHelper;
//use apiv1\models\Signal;
//use apiv1\models\Botsignal;
//use apiv1\models\Userbot;
//use apiv1\models\Signallog;
use console\models\Userpay;
use common\helpers\GeneralHelper;

class SchedularController extends Controller
{

// usr.id=53 => usrdiscordroles=905148136291442808,885839738316128266,886300278838669382,892860718435684392
// mbrdiscordroles=892860718435684392

  /*private function __getDiscordMember($discordUserid, $maxtimes=10) {
		$discordroles = [];
		if (!empty($discordUserid) && ($maxtimes>0)) {
			$discorddata = json_decode( GeneralHelper::getDiscordMember($discordUserid, ''), true);
			Yii::trace('** __getDiscordMember discorddata: '.print_r($discorddata, true));
			if (!empty($discorddata['roles'])) {
				$discordroles = (is_array($discorddata['roles']) ? $discorddata['roles'] : explode(',', $discorddata['roles']));
			} else {
				if (!empty($discorddata['retry_after'])) {
					$waitseconds = 1 +  $discorddata['retry_after'];
					if ($waitseconds > 0) {
						Yii::trace('** __getDiscordMember DISCORD REQUEST RESULT: maxtimes='.$maxtimes.' RATE LIMIT : retry after '.$waitseconds.' seconds! SLEEPING...');
						sleep( $waitseconds );
						Yii::trace('** __getDiscordMember DISCORD REQUEST RESULT: maxtimes='.$maxtimes.' RATE LIMIT : slept for '.$waitseconds.' seconds! RETRYING...');
						$maxtimes--;
						self::__getDiscordMember($discordUserid, $maxtimes); // recursive
					}
				}
			}
		}
		Yii::trace('** __getDiscordMember result: '.print_r($discordroles, true));
		return $discordroles;
	}*/

	/*private function _getDiscordMember($discordUserid) {
		$roles = [];
		if (!empty($discordUserid)) {
			$discordfilelock = '/tmp/discordfilelock';
			while (file_exists($discordfilelock)) {
				Yii::trace('** _getDiscordMember FILE '.$discordfilelock.' EXISTS => sleep a second and recheck till removed..');
				sleep( 1 );
			}
			touch($discordfilelock);
			$roles = self::__getDiscordMember($discordUserid);
			unlink($discordfilelock);
		}
		return $roles;
	}*/

	private function _getDiscordMember($discordUserid) {
		$roles = [];
		if (!empty($discordUserid)) {
			$discorddata = json_decode( GeneralHelper::getDiscordMember($discordUserid, ''), true);
			Yii::trace('** _getDiscordMember discorddata: '.print_r($discorddata, true));
			if (!empty($discorddata['roles'])) {
				$roles = (is_array($discorddata['roles']) ? $discorddata['roles'] : explode(',', $discorddata['roles']));
			} else if (!empty($discorddata['error'])) {
				$roles = $discorddata['error'];
			}
		}
		return $roles;
	}

  public function actionUpdatediscordroles()
  {

		//unlink('/tmp/discordfilelock');

		$origdiscord = $updatediscord = [];
		$alsoupdate = false;
		try {
			Yii::trace('** actionUpdatediscordroles start !');
			$sql = "SELECT usr.id as usrid, usr.usr_discordid, MAX(upy.upy_enddate) as maxenddate, IFNULL(usr.usr_discordroles,'') as usrdiscordroles, IFNULL(mbr.mbr_discordroles,'') as mbrdiscordroles, umb.id as umbid"
						." FROM (user usr, usermember umb, membership mbr, userpay upy)"
						." WHERE umb.umbusr_id=usr.id AND usr.usr_discordid!=0 AND usr.usr_discordroles!=''"
						." AND upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."'"
						." AND umb.umbmbr_id=mbr.id AND mbr.mbr_discordroles>''"
						." GROUP BY upy.upyumb_id HAVING MAX(upy.upy_enddate) < DATE(NOW())";
 			$rows = GeneralHelper::runSql($sql);
			//Yii::trace('** Updatediscordroles-DEL rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				Yii::trace('** Updatediscordroles-DEL nr='.$nr.' row: '.print_r($row, true));
				// get current roles from discord and merge with table ones..
				$currentdiscordroles = [];
				if (!empty($row['usr_discordid'])) {
					$currentdiscordroles = self::_getDiscordMember($row['usr_discordid']);
					Yii::trace('** Updatediscordroles-DEL currentdiscordroles: '.print_r($currentdiscordroles, true));
				}
				$usrdiscordroles = array_unique(array_merge($currentdiscordroles, explode(',', $row['usrdiscordroles'])), SORT_NUMERIC);
				// save as current for later
				$origdiscord[ $row['usr_discordid'] ] = implode(',', $usrdiscordroles);
				// remove stopped membership ones
				$resultsql = $resultdiscord = '';
				if (!empty($row['mbrdiscordroles']) && !empty($row['usrdiscordroles'])) {
					Yii::trace('** Updatediscordroles-DEL mbrroles='.$row['mbrdiscordroles']);
					Yii::trace('** Updatediscordroles-DEL oldroles='.$row['usrdiscordroles']);
					foreach(explode(',', $row['mbrdiscordroles']) as $discordrole) {
						if (($i=array_search($discordrole, $usrdiscordroles)) !== false) {
							Yii::trace('** Updatediscordroles-DEL i='.$i.' will remove: '.$usrdiscordroles[$i]);
							unset($usrdiscordroles[$i]);
						}
					}
				}
				// save new roles if not same as table ones merged with current discord ones
				$newroles = implode(',', $usrdiscordroles);
				Yii::trace('** Updatediscordroles-DEL newroles='.$newroles);
				if ($row['usrdiscordroles'] != $newroles) {
					$sql = "UPDATE user SET usr_discordroles='".$newroles."', updated_at='".time()."', usr_updatedt=NOW() WHERE id=".$row['usrid'];
					$resultsql = ($alsoupdate ? GeneralHelper::runSql($sql) : 'NOT UPDATED by: '.$sql);
					Yii::trace('** Updatediscordroles-DEL sql='.$sql.' => resultsql: '.$resultsql);
					// save changed roles for later to send to discord
					if (!empty($row['usr_discordid'])) {
						$updatediscord[ $row['usr_discordid'] ] = $newroles;
						Yii::trace('** Updatediscordroles-DEL append: updatediscord[ '.$row['usr_discordid'].' ] = '.$newroles);
					}
				}
			}

			Yii::trace('** Updatediscordroles 1 updatediscord: '.print_r($updatediscord, true));

			// Add roles
			$sql = "SELECT usr.id as usrid, usr.usr_discordid, MAX(upy.upy_enddate) as maxenddate, IFNULL(usr.usr_discordroles,'') as usrdiscordroles, IFNULL(mbr.mbr_discordroles,'') as mbrdiscordroles, umb.id as umbid"
						." FROM (user usr, usermember umb, membership mbr, userpay upy)"
						." WHERE umb.umbusr_id=usr.id AND usr.usr_discordid!=0 AND usr.usr_discordroles!=''"
						." AND upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."'"
						." AND umb.umbmbr_id=mbr.id AND mbr.mbr_discordroles>''"
						." GROUP BY upy.upyumb_id HAVING MAX(upy.upy_enddate) >= DATE(NOW())";
			$rows = GeneralHelper::runSql($sql);
			//Yii::trace('** Updatediscordroles-ADD rows: '.print_r($rows, true));
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				Yii::trace('** Updatediscordroles-ADD nr='.$nr.' row: '.print_r($row, true));
				// if not already retrieved from discord above.. do it and merge
				if (!empty($row['usr_discordid']) && empty($origdiscord[$row['usr_discordid']])) {
					$currentdiscordroles = self::_getDiscordMember($row['usr_discordid']);
					Yii::trace('** Updatediscordroles-ADD currentdiscordroles: '.print_r($currentdiscordroles, true));
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
					if (!empty($row['usr_discordid'])) {
						$updatediscord[ $row['usr_discordid'] ] = $newroles;
						Yii::trace('** Updatediscordroles-ADD append: updatediscord[ '.$row['usr_discordid'].' ] = '.$newroles);
					}
				}
			}

			Yii::trace('** Updatediscordroles 2 updatediscord: '.print_r($updatediscord, true));

			// update discord server users with roles
			if (!empty($updatediscord)) {
				foreach($updatediscord as $discordid => $discordroles) {
					if (!empty($origdiscord[$discordid]) && ($origdiscord[$discordid] != $discordroles)) {
						$resultdiscord = ($alsoupdate ? GeneralHelper::saveRolesToDiscordServer($discordid, $discordroles) : 'NO DISCORD UPDATE!');
						Yii::trace('** Updatediscordroles-SERVER id='.$discordid.' : '.$discordroles.' => resultdiscord: '.implode(', ', $resultdiscord));
					} else {
						Yii::trace('** Updatediscordroles-SERVER id='.$discordid.' : '.$discordroles.' equal to orig => not send');
					}
				}
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** Updatediscordroles ERROR => '.$msg);
			return Controller::EXIT_CODE_ERROR;
		}
		return Controller::EXIT_CODE_NORMAL;
	}

}

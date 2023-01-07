<?php

namespace backend\models;

use Yii;
use \backend\models\base\Usermember as BaseUsermember;
use yii\helpers\ArrayHelper;
use \backend\models\Userpay;
use \common\helpers\GeneralHelper;

/**
 * This is the model class for table "usermember".
 */
class Usermember extends BaseUsermember
{

  public function behaviors()
  {
    return ArrayHelper::merge(
      parent::behaviors(),
        [
          # custom behaviors
        ]
    );
  }

  public function rules()
  {
    return ArrayHelper::merge(
      parent::rules(),
        [
          # custom validation rules
        ]
    );
  }

	/*
	* All usermembers of an user OR single usermember
	*/
  public static function getUsermembersOfUser($usrid=0, $activeCheckIsTrue=true, $past=true, $current=true, $umbid=0)
  {
		$result = [];
		try {
	    if (!empty($usrid) || !empty($umbid)) {
				$sql = "SELECT umb.id, umb.umb_active, umb.umb_name as umbname, mbr.id as mbrid, mbr.mbr_title as mbrtitle, usr.id as usrid, usr.username, usr.email,"
							." (SELECT CONCAT(IFNULL(MIN(upy.upy_startdate),''), '|', IFNULL(MAX(upy.upy_enddate),'')) FROM userpay upy"
								." WHERE upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND ("
								.($past ? " (upy.upy_enddate < DATE(NOW()))" : "1=0")
								." OR "
								.($current ? "(upy.upy_enddate >= DATE(NOW()))" : "1=0")
								.")) as upyperiod "
							." FROM (usermember umb, membership mbr, user usr)"
							." WHERE ".(!empty($usrid) ? "umb.umbusr_id=".$usrid : "umb.id=".$umbid)
							.($activeCheckIsTrue ? " AND umb.umb_active=1" : "") . " AND umb.umb_deletedat=0"
							." AND mbr.id=umb.umbmbr_id AND usr.id=umb.umbusr_id";
				$rows = GeneralHelper::runSql($sql);
				$now = time();
				foreach($rows as $nr => $row) if (is_numeric($nr)) {
					$result[ $row['id'] ] = $row;
				}
			}
		} catch(\Exception $e) {
			$msg = 'runSql Error: '.$e->getMessage();
			Yii::trace( $msg );
			$result['error'] = $msg;
    }
		Yii::trace('** getUsermembersOfUser result: '.print_r($result, true));
		return $result;
  }

  public function getUsedSignalCountsOfUsermember($umbid=0, $usrid=0)
  {
    $result = [];
    try {
      if (!empty($umbid) || !empty($usrid)) {
        $sql = "SELECT ubt.ubtumb_id, count(*) as countsignal"
              ." FROM botsignal bsg, userbot ubt "
              ." WHERE ubt.ubtumb_id=".$umbid." AND ubt.ubt_deletedat=0"
              ." AND bsg.bsgubt_id=ubt.id AND bsg.bsgsig_id>0 AND bsg.bsg_deletedat=0"
              ." GROUP BY ubt.ubtumb_id";
        $rows = GeneralHelper::runSql($sql);
        foreach($rows as $nr => $row) if (is_numeric($nr)) $result[ $row['ubtumb_id'] ] = $row['countsignal'];
        Yii::trace('** getUsedSignalCountsOfUsermember umbid='.$umbid.' rows:'.print_r($rows,true).' result:'.print_r($result,true));
      }
    } catch (\Exception $e) {
      $result['error'] = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
    }
    //Yii::trace('** getCurrentMemberships result: '.print_r($result, true));
    return $result;
  }

	public function getUsermemberFromBotsignal($bsgid) {
    $result = [];
    try {
      if (!empty($bsgid)) {
				$past = $current = true;
        $sql = "SELECT usr.id as usrid, usr.username, umb.id as umbid, umb.umb_name as umbname, mbr.id as mbrid, mbr.mbr_title as mbrtitle, ubt.ubt_3cbotid as 3cbotid,  "
							." (SELECT CONCAT(IFNULL(MIN(upy.upy_startdate),''), '|', IFNULL(MAX(upy.upy_enddate),'')) FROM userpay upy"
                ." WHERE upy.upyumb_id=umb.id AND upy.upy_state='".Userpay::UPY_STATE_PAID."' AND ("
                .($past ? " (upy.upy_enddate < DATE(NOW()))" : "1=0")
                ." OR "
                .($current ? "(upy.upy_enddate >= DATE(NOW()))" : "1=0")
                .")) as upyperiod "
              ." FROM (botsignal bsg, userbot ubt, usermember umb, membership mbr, user usr)"
							." WHERE bsg.id=".$bsgid." AND ubt.id=bsg.bsgubt_id AND umb.id=ubt.ubtumb_id AND mbr.id=umb.umbmbr_id AND usr.id=umbusr_id"
							." LIMIT 1";
        $rows = GeneralHelper::runSql($sql);
        foreach($rows as $nr => $row) if (is_numeric($nr)) $result = $row;
      }
    } catch (\Exception $e) {
      $result['error'] = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
    }
    //Yii::trace('** getCurrentMemberships result: '.print_r($result, true));
    return $result;
	}

}

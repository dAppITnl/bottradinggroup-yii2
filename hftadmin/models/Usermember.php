<?php

namespace hftadmin\models;

use Yii;
use \backend\models\Usermember as BackendUsermember;
use yii\helpers\ArrayHelper;
use hftadmin\models\Userpay;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "usermember".
 */
class Usermember extends BackendUsermember
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


  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return array_merge(parent::attributeLabels(), [
      'umb_name' => Yii::t('models', 'My Name'),
      'umb_roles' => Yii::t('models', 'Roles'),
      'umb_startdate' => Yii::t('models', 'Startdate'),
      'umb_enddate' => Yii::t('models', 'Enddate'),
      'umb_remarks' => Yii::t('models', 'Remarks'),
    ]);
  }

  /**
   * @inheritdoc
   */
  public function attributeHints()
  {
    return []; /*array_merge(parent::attributeHints(), [
      'umbmbr_id' => Yii::t('models', 'Membership'),
      'umb_name' => Yii::t('models', 'Name'),
      'umb_roles' => Yii::t('models', 'Roles'),
      'umb_startdate' => Yii::t('models', 'Startdate'),
      'umb_enddate' => Yii::t('models', 'Enddate'),
      'umb_remarks' => Yii::t('models', 'Remarks'),
    ]);*/
  }

  /*
  * Find curent or last paid membership id for user
	* 8jan22 no longer used, replaced by findUsermemberForUserAndMembership()
  */
  public function findPaidUsermemberForUser($usrid=0, $mbrid=0, $onlyCurrent=false)
  {
    $result = [];
		Yii::trace('** findPaidUsermemberForUser usrid='.$usrid.' mbrid='.$mbrid);
    if (!empty($usrid) && !empty($mbrid)) {
      // umb_startdate is initial date of this subscription, never changed after 1st paid period
      // umb_enddate is last paid enddate, calc after payment, so can extend multiple periods
      // umb_paystartdate and umb_payenddate is actual paying period during payment and temporarily, copied to upy_startdate and upy_enddate of the payment
      $sql = "SELECT umb.id, umb.umbmbr_id, umb.umb_startdate, umb.umb_enddate, upy.upy_startdate, upy.upy_enddate"
            ." FROM usermember umb, userpay upy"
            ." WHERE umb.umbmbr_id IN (SELECT mbr1.id FROM membership mbr1 WHERE mbr1.mbr_groupnr=(SELECT mbr2.mbr_groupnr FROM membership mbr2 WHERE mbr2.id=".$mbrid." AND mbr2.mbr_deletedat=0))"
            ." AND umb.umbusr_id=".$usrid." AND umb.umb_deletedat=0"
            .($onlyCurrent ? " AND NOW() BETWEEN umb.umb_startdate AND umb.umb_enddate" : " AND umb.umb_startdate <= NOW()") // future paid startdate not allowed
            ." AND upy.upyumb_id=umb.id AND upy.upy_state='".UserPay::UPY_STATE_PAID."'"
            ." ORDER BY upy.upy_enddate DESC, umb.id DESC"
            ." LIMIT 1";
      $rows = GeneralHelper::runSql($sql);
      foreach($rows as $id => $row) if (is_numeric($id)) $result = $row;
      Yii::trace('** findPaidUsermemberForUser result:'.print_r($result,true));
    }
    return $result;
  }

	/*
	* Find usermember for user and memebrship for next subscription
	* user + mbr + deletedat is unique
	*/
	public function findUsermemberForUserAndMembership($usrid=0, $mbrid=0)
	{
		$result = [];
		try {
			Yii::trace('** findUsermemberForUserAndMembership usrid='.$usrid.' mbrid='.$mbrid);
			if (!empty($usrid) && !empty($mbrid)) {
				// umb_startdate is updated to today if new or umb_enddate is in past
				// umb_enddate is last enddate, so can extend multiple periods or manually extend if necessary (and commented!) not reflecting a usrpay record, so userpay no longer leads.
				// umb_paystartdate and umb_payenddate is actual paying period during payment and temporarily, copied to upy_startdate and upy_enddate of the payment
				$sql = "SELECT umb.id, umb.umbmbr_id, umb.umbupy_id,"
							." (SELECT IFNULL(MAX(upy.upy_enddate),'') FROM userpay upy WHERE upy.upyumb_id=umb.id AND upy.upy_state='".UserPay::UPY_STATE_PAID."' AND upy.upy_deletedat=0) as lastenddate"
							." FROM usermember umb"
							." WHERE umb.umbmbr_id IN (SELECT mbr1.id FROM membership mbr1 WHERE mbr1.mbr_groupnr=(SELECT mbr2.mbr_groupnr FROM membership mbr2 WHERE mbr2.id=".$mbrid." AND mbr2.mbr_deletedat=0))"
							." AND umb.umbusr_id=".$usrid." AND umb.umb_deletedat=0";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) { $result = $row; }
				Yii::trace('** findUsermemberForUserAndMembership result:'.print_r($result,true));
			}
		} catch (\Exception $e) {
      $result['error'] = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
		}
		return $result;
	}


/*	public function getUsedSignalCountsOfUsermember($umbid=0, $usrid=0)
	{
		$result = [];
		try {
			if (!empty($umbid) || !$empty($usrid)) {
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
	} */

  /*public function getUsermembersOfUser($userId=0, $activeCheckIsTrue=true)
	{
		if (!empty($userId)) {
			$where = ['umbusr_id'=>$userId, 'umb_deletedat'=>0];
			if ($activeCheckIsTrue) $where = array_merge($where, ['umb_active'=>1]);
			//Yii::trace('** getUsermembersOfUser userId='.$userId.' check='.($activeCheckIsTrue?'True':'False').' where='.print_r($where,true));
			$model = Usermember::find()->where($where)->all();
			//Yii::trace('** getUsermembersOfUser count='.count($model));
      return $model;
    } else {
			return null;
    }
	}*/

  public function getHistoryUsermemberships($usrid=0, $activeCheckIsTrue=true)
	{
		$model = new Usermember;
		try {
			if (!empty($usrid)) {
				$where = ['umbusr_id'=>$usrid, 'umb_deletedat'=>0];
				$where2 = ['<', 'umb_enddate', new \yii\db\Expression('DATE(NOW())')];
				if ($activeCheckIsTrue) $where = array_merge($where, ['umb_active'=>1]);
				Yii::trace('** getHistoryUsermemberships userid='.$usrid.' check='.($activeCheckIsTrue?'True':'False').' where='.print_r($where,true));
				$model = Usermember::find()->where($where)->andwhere($where2)->all();
				Yii::trace('** getHistoryUsermemberships count='.count($model));
      }
    } catch (\Exception $e) {
      $model->addError((isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage());
    }
		return $model;
	}

}

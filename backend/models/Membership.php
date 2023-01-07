<?php

namespace backend\models;

use Yii;
use \backend\models\base\Membership as BaseMembership;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use common\helpers\GeneralHelper;
use backend\models\Userpay;
use backend\models\User;

/**
 * This is the model class for table "membership".
 */
class Membership extends BaseMembership
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

	public function getAdminMembershipData($usrid=0) {
		$result = [];
		try {
			$usrid = (!empty($usrid) ? $usrid : Yii::$app->user->id);
			$minimalAdminlevel = (GeneralHelper::allowWhenMinimal(User::USR_SITELEVEL_ADMIN, $usrid) == 'true');
			$sql = "SELECT mbr.id, mbr.mbr_code, mbr.mbr_title, mbr.mbr_language, mbr.mbr_groupnr, mbr.mbr_order,"
						." (SELECT count(*) FROM (usermember umb1, userpay upy1) WHERE umb1.umbmbr_id=mbr.id AND umb1.umb_active=1 AND umb1.umb_deletedat=0"
						."  AND upy1.upyumb_id=umb1.id AND upy1.upy_state='".Userpay::UPY_STATE_PAID."' AND (DATE(NOW()) BETWEEN upy1.upy_startdate and upy1.upy_enddate) AND upy1.upy_payamount>0 AND upy1.upy_deletedat=0) as umbcountpaid,"
						." (SELECT count(*) FROM (usermember umb2, userpay upy2) WHERE umb2.umbmbr_id=mbr.id AND umb2.umb_active=1 AND umb2.umb_deletedat=0"
						."  AND upy2.upyumb_id=umb2.id AND upy2.upy_state='".Userpay::UPY_STATE_PAID."' AND (DATE(NOW()) BETWEEN upy2.upy_startdate and upy2.upy_enddate) AND upy2.upy_payamount=0 AND upy2.upy_deletedat=0) as umbcountfree"
						//." (SELECT count(*) FROM usermember umb WHERE umb.umbmbr_id=mbr.id AND umb.umb_active=1 AND umb.umb_deletedat=0) as umbcount"
						." FROM membership mbr"
						." WHERE (mbr.mbr_active=1".($minimalAdminlevel ? " OR mbr.mbr_active4admin=1" : "").") AND mbr.mbr_deletedat=0"
						." ORDER BY mbr.mbr_groupnr ASC, mbr.mbr_order ASC";
			$rows = GeneralHelper::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr)) $result[ $row['id']] = $row;
		} catch(\Exception $e) {
			$msg = 'Error: '.$e->getMessage();
			Yii::trace('** getAdminMembershipData msg='.$msg );
			throw new \Exception($msg);
		}
		return $result;
	}

	public function getStatisticReportData($yearmonth='') {
		$result = [];
		try {
			if (!empty($yearmonth)) {
				$year=substr($yearmonth,0,4); $month=substr($yearmonth,4);
				$startdate=$year.'-'.($month<10 ? '0':'').$month.'-01'; $enddate=date('Y-m-t', strtotime($startdate));
				// general sql part for requested period
				$sql_1 = " FROM userpay"
								." WHERE upy_state='".Userpay::UPY_STATE_PAID."'" // AND upy_providermode='live'"
								." AND ((upy_startdate<='".$startdate."' AND upy_enddate>='".$startdate."')"
								." OR ('".$startdate."'<=upy_startdate AND '".$enddate."'>=upy_startdate))";

				// total subscriptions count
				$sql = "SELECT COUNT(DISTINCT upyusr_id) as totalcount" . $sql_1;
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result['totalmbrs'] = $row['totalcount'];

				// total count per membership
				$sql = "SELECT upymbr_id, COUNT(DISTINCT upyusr_id) as totalmbr,"
							." (SELECT mbr.mbr_title FROM membership mbr WHERE mbr.id=upymbr_id) as mbrtitle"
							. $sql_1 . " GROUP BY upymbr_id";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result['mbr'][ $row['upymbr_id'] ] = ['title'=>$row['mbrtitle'], 'totalmbr'=>$row['totalmbr']];

				// counts per periodcode
				$sql = "SELECT upy_percode, COUNT(DISTINCT upyusr_id) as totalpercode" . $sql_1 . " GROUP BY upy_percode";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result['percode'][ $row['upy_percode'] ] = $row['totalpercode'];

				// paid amounts in fiat and crypto
				$sql = "SELECT IFNULL(upysym_pay_id,'') as payid, IFNULL(upysym_crypto_id,'') as cryptoid, IFNULL(SUM(upy_payamount),'') as fiattotal, IFNULL(SUM(upy_cryptoamount),'') as cryptototal,"
							." (SELECT fiat.sym_code FROM symbol fiat WHERE fiat.id=upysym_pay_id) as fiatsymbol,"
							." (SELECT crypto.sym_code FROM symbol crypto WHERE crypto.id=upysym_crypto_id) as cryptosymbol"
							. $sql_1 . " GROUP BY upysym_pay_id, upysym_crypto_id";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result['amount'][] = [
					'fiattotal'  =>$row['fiattotal'],   'fiatsymbol'  =>$row['fiatsymbol'],   $row['payid'],
					'cryptototal'=>$row['cryptototal'], 'cryptosymbol'=>$row['cryptosymbol'], $row['cryptoid']
				];
			}
		} catch(\Exception $e) {
			$msg = 'Error: '.$e->getMessage();
			Yii::trace('** getStatisticReportData msg='.$msg );
			throw new \Exception($msg);
    }
		return $result;
	}

// ---------------

	public function getMemberships($language='', $usrid=0) {
		$result = [];
		try {
			$usrid = (!empty($usrid) ? $usrid : Yii::$app->user->id);
			$minimalAdminlevel = (GeneralHelper::allowWhenMinimal(User::USR_SITELEVEL_ADMIN, $usrid) == 'true');
			$sql = "SELECT id, mbr_title FROM membership WHERE (mbr_active=1".($minimalAdminlevel ? " OR mbr_active4admin=1" : "").") AND mbr_deletedat=0"
						." AND mbr_language=".(!empty($language) ? "'".$language."'" : "(SELECT usr_language FROM user WHERE id=". \Yii::$app->user->id .")")
						." ORDER BY mbr_order ASC";
      $rows = GeneralHelper::runSql($sql);
      foreach($rows as $nr => $row) if (is_numeric($nr)) $result[ $row['id'] ] = $row['mbr_title'];
    } catch(\Exception $e) {
      $msg = 'Error: '.$e->getMessage();
      Yii::trace('** getUsersWithinLevels msg='.$msg );
      throw new \Exception($msg);
    }
		return $result;
	}


  /**
  * Finds the Membership model based on mbr_code value.
  * If the model is not found, a 404 HTTP exception will be thrown.
  * @param string $code
  * @return Membership the loaded model
  * @throws HttpException if the model cannot be found
  */
  public function findModelByCodeAndLanguage($code='', $language='', $usrid=0)
  {
		Yii::trace('** findModelByCodeAndLanguage 1 code='.$code.' language='.$language.' usrid='.$usrid.'='.Yii::$app->user->id);
		$usrid = (!empty($usrid) ? $usrid : Yii::$app->user->id);
		$minimalAdminlevel = (GeneralHelper::allowWhenMinimal(User::USR_SITELEVEL_ADMIN, $usrid) == 'true');
		Yii::trace('** findModelByCodeAndLanguage 2 code='.$code.' language='.$language.' usrid='.$usrid.'='.Yii::$app->user->id.' => minAdminlevel='.($minimalAdminlevel ? 'True':'False'));
		$where = ['mbr_deletedat'=>0];
		if (!empty($code)) $where = array_merge($where, ['mbr_code'=>$code]);
		if (!empty($language)) $where = array_merge($where, ['mbr_language'=>$language]);
		Yii::trace('findModelByCodeAndLanguage where=' . print_r($where, true));

		if ($minimalAdminlevel) {
			$andwhere = ['or', ['mbr_active'=>1], ['mbr_active4admin'=>'1']];
		} else {
			$andwhere = ['mbr_active'=>1];
		}
		Yii::trace('findModelByCodeAndLanguage andwhere=' . print_r($andwhere, true));

    if (($model = Membership::find()->where($where)->andWhere($andwhere)->orderBy(['mbr_language'=>SORT_ASC, 'mbr_order'=>SORT_ASC])->all()) !== null) {
			Yii::trace('findModelByCodeAndLanguage code='.$code.' language='.$language.' rows='.count($model));
      return $model;
    } else {
      throw new HttpException(404, 'The requested membership(s) by code='.$code.' and language='.$language.' does not exist or is deleted.');
    }
  }
}

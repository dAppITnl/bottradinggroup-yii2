<?php

namespace hftadmin\models;

use Yii;
use backend\models\Userpay as BackendUserpay;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "userpay".
 */
class Userpay extends BackendUserpay
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

	public function getPaymentsOfUsermembers($umbids='', $onlyPaid=true, $past=true, $current=true)
  {
    $result = [];
    try {
      if (!empty($umbids)) {
        $sql = "SELECT upy.id, upy.upyumb_id, upy.upy_startdate, upy.upy_enddate, upy.upy_percode, upy.upy_payamount, upy.upy_payref, upy.upy_maxsignals,"
							." sym.sym_code as fiatcode, (SELECT sc.sym_code FROM symbol sc WHERE sc.id=upy.upysym_crypto_id) as cryptocode"
              ." FROM (userpay upy, symbol sym)"
              ." WHERE upy.upyumb_id IN (".$umbids.")" . ($onlyPaid ? " AND upy.upy_state='".self::UPY_STATE_PAID."'" : "") . " AND upy.upy_deletedat=0"
              ." AND sym.id=upysym_pay_id AND ("
              .($past    ? " (upy.upy_enddate < DATE(NOW())) " : "")
              .($past && $current ? " OR " : "")
              .($current ? " (upy.upy_enddate >= DATE(NOW()))" : "")
              .(!$past && !$current ? "1=0" : "") . ")"
							." ORDER BY upy.upy_startdate DESC, upy.upy_enddate DESC";
        $rows = GeneralHelper::runSql($sql);
        foreach($rows as $nr => $row) if (is_numeric($nr)) {
          $result[ $row['upyumb_id'] ][] = $row;
        }
      }
    } catch(\Exception $e) {
      $msg = 'runSql Error: '.$e->getMessage();
      Yii::trace( $msg );
      $result['error'] = $msg;
    }
    Yii::trace('** getPaymentsOfUsermember result: '.print_r($result, true));
    return $result;
  }

  public function getCurrentUserpayOfUsermember($umbid=0) {
    $result = [];
    try {
      Yii::trace('** getCurrentUserpayOfUsermember umbid='.$umbid);
      if (!empty($umbid)) {
        $sql = "SELECT upy.id, upy.upy_maxsignals"
              ." FROM userpay upy"
              ." WHERE upy.upyumb_id=".$umbid." AND upy.upy_deletedat=0 AND (DATE(NOW()) BETWEEN upy.upy_startdate AND upy.upy_enddate)"
              ." ORDER BY upy.upy_enddate DESC LIMIT 1"; // get latest enddate if doubles..
        $rows = GeneralHelper::runSql($sql);
        Yii::trace('** getCurrentUserpayOfUsermember rows:'.print_r($rows,true));
        foreach($rows as $nr => $row) if (is_numeric($nr)) { $result = $row; }
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      $result['error'] = $msg;
    }
    Yii::trace('** getCurrentUserpayOfUsermember result:'.print_r($result,true));
    return $result;
  }

}

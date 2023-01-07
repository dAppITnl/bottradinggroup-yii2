<?php

namespace frontend\models;

use Yii;
use \backend\models\Cryptoaddress as BackendCryptoaddress;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "cryptoaddress".
 */
class Cryptoaddress extends BackendCryptoaddress
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

	public function getCryptoToAddresses($prlcadIds='', $payprovider='', $withCode=true) {
		$result = [];
		try {
			Yii::trace('** getCryptoToAddresses prlcadIds='.$prlcadIds.' payprovider='.$payprovider.' withCode='.($withCode?'True':'False'));
			if (!empty($prlcadIds) && !empty($payprovider)) {
				if ($payprovider == GeneralHelper::PAYPROVIDER_CRYPTOWALLET) {
					
					// ToDo!!    
					
				}
				$prlcadIds = implode("','", (is_array($prlcadIds) ? $prlcadIds : explode(',',$prlcadIds)));
				$sql = "SELECT cad.id, ".($withCode ? "IFNULL(CONCAT(cad.cad_code,': ',cad.cad_name),'') as " : "cad.")."cad_name, cad.cad_decimals, sym.sym_code"
							." FROM (cryptoaddress cad, symbol sym)"
							." WHERE (cad.id IN ('".$prlcadIds."')) AND (cad_payprovider='".$payprovider."') AND cad.cad_active=1 AND cad.cad_deletedat=0"
							.((! GeneralHelper::allowWhenMinimal(User::USR_SITELEVEL_ADMIN)=='true') ? " AND cad.cad_ismainnet=1" : "")
							." AND sym.id=cad.cadsym_id AND sym.sym_deletedat=0";
				$rows = GeneralHelper::runSql($sql,false,true);
				foreach($rows as $id => $row) if (is_numeric($id)) $result[ $row['id'].'|'.$row['sym_code'].'|'.$row['cad_decimals'] ] = $row['cad_name'];
			}
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** getCryptoToAddresses ERROR msg='.$msg);
      $result['error'] = $msg;
    }
		Yii::trace('** getCryptoToAddresses result: '.print_r($result, true));
		return $result;
	}

	public function getCadData($cadid='') {
		$result = [];
		try {
			Yii::trace('** getCadData cadid='.$cadid);
			if (!empty($cadid)) {
				$sql = "SELECT cad.id, cad.cad_payprovider as payprovider, cad.cad_ismainnet as ismainnet, cad.cad_decimals as decimals, sym.sym_symbol as symbol, cad.cadsym_id as cadsymid"
							." FROM (cryptoaddress cad, `symbol` sym) WHERE cad.id=".$cadid." AND cad.cad_active=1 AND cad.cad_deletedat=0"
							." AND sym.id=cad.cadsym_id AND sym.sym_deletedat=0";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $id => $row) if (is_numeric($id)) $result = $row;
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** getCadData ERROR msg='.$msg);
			$result['error'] = $msg;
		}
		return $result;
	}

}

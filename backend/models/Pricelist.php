<?php

namespace backend\models;

use Yii;
use \backend\models\base\Pricelist as BasePricelist;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "pricelist".
 */
class Pricelist extends BasePricelist
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

  public function getSymbolsOfType($symtype='', $onlyCode=false)
  {
    $result = [];
    try {
			if (!empty($symtype)) {
	      $sql = "SELECT id, IFNULL(".($onlyCode ? "sym_code" : "CONCAT(sym_code,' = ',sym_name)").",'') as title FROM symbol WHERE sym_type='".$symtype."' AND sym_ispair=0 AND sym_deletedat=0 ORDER BY sym_code ASC";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result[ $row['id'] ] = $row['title'];
      	Yii::trace('** getCryptoSymbols result: '.print_r($result, true));
			}
    } catch (\Exception $e) {
      $result[] = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
    }
    return $result;
  }


}

<?php

namespace backend\models;

use Yii;
use \backend\models\base\Symbol as BaseSymbol;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "symbol".
 */
class Symbol extends BaseSymbol
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


	public function getSymbols($onlyQuote=false)
	{
		$where = ['sym_type'=>self::SYM_TYPE_CRYPTO, 'sym_ispair'=>0, 'sym_deletedat'=>0];
		if ($onlyQuote) $where = array_merge($where, ['sym_isquote'=>1]);
		return ArrayHelper::map(Symbol::find()->select(['id', 'CONCAT(sym_code,\' = \',sym_name) as sym_name'])->where($where)->orderby(['sym_name'=>SORT_ASC])->all(), 'id', 'sym_name');
	}


}

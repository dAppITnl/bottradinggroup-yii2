<?php

namespace hftadmin\models;

use Yii;
use \backend\models\Symbol as BackendSymbol;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "symbol".
 */
class Symbol extends BackendSymbol
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

}

<?php

namespace apiv1\models;

use Yii;
use \backend\models\Signal as BackendSignal;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signal".
 */
class Signal extends BackendSignal
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

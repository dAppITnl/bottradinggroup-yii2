<?php

namespace backend\models;

use Yii;
use \backend\models\base\SignalMembership as BaseSignalMembership;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signal_membership".
 */
class SignalMembership extends BaseSignalMembership
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

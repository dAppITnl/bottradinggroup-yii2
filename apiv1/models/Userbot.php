<?php

namespace apiv1\models;

use Yii;
use \backend\models\Userbot as BackendUserbot;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "userbot".
 */
class Userbot extends BackendUserbot
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

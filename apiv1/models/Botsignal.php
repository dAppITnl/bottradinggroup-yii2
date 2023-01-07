<?php

namespace apiv1\models;

use Yii;
use \backend\models\Botsignal as BackendBotsignal;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "botsignal".
 */
class Botsignal extends BackendBotsignal
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

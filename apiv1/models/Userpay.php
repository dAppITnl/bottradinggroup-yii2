<?php

namespace apiv1\models;

use Yii;
use \backend\models\Userpay as BackendUserpay;
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

}

<?php

namespace backend\models;

use Yii;
use \backend\models\base\Usersignal as BaseUsersignal;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "usersignal".
 */
class Usersignal extends BaseUsersignal
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

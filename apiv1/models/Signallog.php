<?php

namespace apiv1\models;

use Yii;
use \backend\models\Signallog as BackendSignallog;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signallog".
 */
class Signallog extends BackendSignallog
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

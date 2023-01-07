<?php

namespace backend\models;

use Yii;
use \backend\models\base\Category as BaseCategory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 */
class Category extends BaseCategory
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

<?php

namespace frontend\models;

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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        /*    'id' => Yii::t('models', 'ID'),
            'bsgubt_id' => Yii::t('models', 'Userbot'),
            'bsgsig_id' => Yii::t('models', 'Signal'),
            'bsg_active' => Yii::t('models', 'Active'),
            'bsg_remarks' => Yii::t('models', 'Remarks'),
            'bsg_lock' => Yii::t('models', 'Lock'),
            'bsg_createdat' => Yii::t('models', 'CreatedAt'),
            'bsg_createdt' => Yii::t('models', 'Created'),
            'bsgusr_created_id' => Yii::t('models', 'CreatedBy'),
            'bsg_updatedat' => Yii::t('models', 'UpdatedAt'),
            'bsg_updatedt' => Yii::t('models', 'Updated'),
            'bsgusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'bsg_deletedat' => Yii::t('models', 'DeletedAt'),
            'bsg_deletedt' => Yii::t('models', 'Deleted'),
            'bsgusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'bsgubt_id' => Yii::t('models', 'Userbot'),
            'bsgsig_id' => Yii::t('models', 'Signal'),
            'bsg_active' => Yii::t('models', 'Active'),
            'bsg_remarks' => Yii::t('models', 'Remarks'),
            'bsg_lock' => Yii::t('models', 'Lock'),
            'bsg_createdat' => Yii::t('models', 'CreatedAt'),
            'bsg_createdt' => Yii::t('models', 'Created'),
            'bsgusr_created_id' => Yii::t('models', 'CreatedBy'),
            'bsg_updatedat' => Yii::t('models', 'UpdatedAt'),
            'bsg_updatedt' => Yii::t('models', 'Updated'),
            'bsgusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'bsg_deletedat' => Yii::t('models', 'DeletedAt'),
            'bsg_deletedt' => Yii::t('models', 'Deleted'),
            'bsgusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }


}

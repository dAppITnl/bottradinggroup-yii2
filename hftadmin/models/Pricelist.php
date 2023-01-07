<?php

namespace hftadmin\models;

use Yii;
use \backend\models\Pricelist as BackendPricelist;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pricelist".
 */
class Pricelist extends BackendPricelist
{
	const HFTADMIN_CATEGORIES_PRL = [31,32];

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
            'prlmbr_id' => Yii::t('models', 'Membership'),
            'prlcat_id' => Yii::t('models', 'Category'),
            'prl_active' => Yii::t('models', 'Active'),
						'prl_active4admin' => Yii::t('models', 'Active4Admin'),
            'prl_name' => Yii::t('models', 'Name'),
            'prl_pretext' => Yii::t('models', 'PreText'),
            'prl_posttext' => Yii::t('models', 'PostText'),
            'prl_startdate' => Yii::t('models', 'Startdate'),
            'prl_enddate' => Yii::t('models', 'Enddate'),
            'prl_percode' => Yii::t('models', 'Period'),
						'prl_maxsignals' => Yii::t('models', 'MaxSignals'),
            'prl_allowedtimes' => Yii::t('models', 'AllowedTimes'),
            'prl_discountcode' => Yii::t('models', 'Discountcode'),
            'prlsym_id' => Yii::t('models', 'Symbol'),
            'prl_price' => Yii::t('models', 'Price'),
            'prl_remarks' => Yii::t('models', 'Remarks'),
            'prl_lock' => Yii::t('models', 'Lock'),
            'prl_createdat' => Yii::t('models', 'CreatedAt'),
            'prl_createdt' => Yii::t('models', 'Created'),
            'prlusr_created_id' => Yii::t('models', 'CreatedBy'),
            'prl_updatedat' => Yii::t('models', 'UpdatedAt'),
            'prl_updatedt' => Yii::t('models', 'Updated'),
            'prlusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'prl_deletedat' => Yii::t('models', 'DeletedAt'),
            'prl_deletedt' => Yii::t('models', 'Deleted'),
            'prlusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

   /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'prlmbr_id' => Yii::t('models', 'Membership'),
            'prlcat_id' => Yii::t('models', 'Category'),
            'prl_active' => Yii::t('models', 'Active'),
						'prl_active4admin' => Yii::t('models', 'Active4Admin'),
            'prl_name' => Yii::t('models', 'Name'),
            'prl_pretext' => Yii::t('models', 'PreText'),
            'prl_posttext' => Yii::t('models', 'PostText'),
            'prl_startdate' => Yii::t('models', 'Startdate'),
            'prl_enddate' => Yii::t('models', 'Enddate'),
            'prl_percode' => Yii::t('models', 'Period'),
            'prl_allowedtimes' => Yii::t('models', 'AllowedTimes'),
            'prl_discountcode' => Yii::t('models', 'Discountcode'),
            'prlsym_id' => Yii::t('models', 'Symbol'),
            'prl_price' => Yii::t('models', 'Price'),
            'prl_remarks' => Yii::t('models', 'Remarks'),
            'prl_lock' => Yii::t('models', 'Lock'),
            'prl_createdat' => Yii::t('models', 'CreatedAt'),
            'prl_createdt' => Yii::t('models', 'Created'),
            'prlusr_created_id' => Yii::t('models', 'CreatedBy'),
            'prl_updatedat' => Yii::t('models', 'UpdatedAt'),
            'prl_updatedt' => Yii::t('models', 'Updated'),
            'prlusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'prl_deletedat' => Yii::t('models', 'DeletedAt'),
            'prl_deletedt' => Yii::t('models', 'Deleted'),
            'prlusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

}

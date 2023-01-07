<?php

namespace hftadmin\models;

use Yii;
use \backend\models\Signal as BackendSignal;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signal".
 */
class Signal extends BackendSignal
{

	const HFTADMIN_CATEGORIES_SIG = [28,29,30]; // category id's of type Signal for HFTAdmin
	const HFTADMIN_MEMBERSHIPS = [3]; // membership id's for HFTAdmin

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
            'sigcat_ids' => Yii::t('models', 'Categories'),
            'sigsym_base_id' => Yii::t('models', 'SymbolBase'),
            'sigsym_quote_id' => Yii::t('models', 'SymbolQuote'),
            'sigmbr_ids' => Yii::t('models', 'Memberships'),
            'sig_active' => Yii::t('models', 'Active'),
						'sig_active4admin' => Yii::t('models', 'Active4Admin'),
            'sig_maxbots' => Yii::t('models', 'MaxBots'),
            'sig_code' => Yii::t('models', 'Signalcode'),
            'sig_name' => Yii::t('models', 'Name'),
            'sig_3cactiontext' => Yii::t('models', '3CActiontext'),
            'sig_3callowedquotes' => Yii::t('models', '3CAllowedQuoteCoins'),
            'sig_description' => Yii::t('models', 'Description'),
            'sig_remarks' => Yii::t('models', 'Remarks'),
            'sig_lock' => Yii::t('models', 'Lock'),
            'sig_createdt' => Yii::t('models', 'Created'),
            'sig_createdat' => Yii::t('models', 'CreatedAt'),
            'sigusr_created_id' => Yii::t('models', 'CreatedBy'),
            'sig_updatedat' => Yii::t('models', 'UpdatedAt'),
            'sig_updatedt' => Yii::t('models', 'Updated'),
            'sigusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'sig_deletedat' => Yii::t('models', 'DeletedAt'),
            'sig_deletedt' => Yii::t('models', 'Deleted'),
            'sigusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

   /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'sigcat_ids' => Yii::t('models', 'Categories'),
            'sigsym_base_id' => Yii::t('models', 'SymbolBase'),
            'sigsym_quote_id' => Yii::t('models', 'SymbolQuote'),
            'sigmbr_ids' => Yii::t('models', 'Memberships'),
            'sig_active' => Yii::t('models', 'Active'),
						'sig_active4admin' => Yii::t('models', 'Active4Admin'),
            'sig_maxbots' => Yii::t('models', 'MaxBots'),
            'sig_code' => Yii::t('models', 'Signalcode'),
            'sig_name' => Yii::t('models', 'Name'),
            'sig_3cactiontext' => Yii::t('models', '3CActiontext'),
            'sig_3callowedquotes' => Yii::t('models', '3CAllowedQuoteCoins'),
            'sig_description' => Yii::t('models', 'Description'),
            'sig_remarks' => Yii::t('models', 'Remarks'),
            'sig_lock' => Yii::t('models', 'Lock'),
            'sig_createdt' => Yii::t('models', 'Created'),
            'sig_createdat' => Yii::t('models', 'CreatedAt'),
            'sigusr_created_id' => Yii::t('models', 'CreatedBy'),
            'sig_updatedat' => Yii::t('models', 'UpdatedAt'),
            'sig_updatedt' => Yii::t('models', 'Updated'),
            'sigusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'sig_deletedat' => Yii::t('models', 'DeletedAt'),
            'sig_deletedt' => Yii::t('models', 'Deleted'),
            'sigusr_deleted_id' => Yii::t('models', 'DeletedBy'),  */
        ]);
    }

}

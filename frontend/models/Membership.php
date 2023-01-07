<?php

namespace frontend\models;

use Yii;
use \backend\models\Membership as BackendMembership;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "membership".
 */
class Membership extends BackendMembership
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
            'mbrcat_id' => Yii::t('models', 'Category'),
            'mbr_language' => Yii::t('models', 'Language'),
            'mbr_active' => Yii::t('models', 'Active'),
						'mbr_active4admin' => Yii::t('models', 'Active4Admin'),
            'mbr_order' => Yii::t('models', 'Order'),
            'mbr_code' => Yii::t('models', 'Code'),
            'mbr_title' => Yii::t('models', 'Title'),
            'mbr_groupnr' => Yii::t('models', 'Groupnr'),
            'mbr_cardbody' => Yii::t('models', 'Cardbody'),
            'mbr_detailbody' => Yii::t('models', 'Detailbody'),
            'mbr_discordroles' => Yii::t('models', 'DiscordRoles'),
						'mbr_discordlogchanid' => Yii::t('models', 'DiscordLogChannelID'),
            'mbr_remarks' => Yii::t('models', 'Remarks'),
            'mbr_lock' => Yii::t('models', 'Lock'),
            'mbr_createdat' => Yii::t('models', 'CreatedAt'),
            'mbr_createdt' => Yii::t('models', 'Created'),
            'mbrusr_created_id' => Yii::t('models', 'CreatedBy'),
            'mbr_updatedat' => Yii::t('models', 'UpdatedAt'),
            'mbr_updatedt' => Yii::t('models', 'Updated'),
            'mbrusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'mbr_deletedat' => Yii::t('models', 'DeletedAt'),
            'mbr_deletedt' => Yii::t('models', 'Deleted'),
            'mbrusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'mbrcat_id' => Yii::t('models', 'Category'),
            'mbr_language' => Yii::t('models', 'Language'),
            'mbr_active' => Yii::t('models', 'Active'),
						'mbr_active4admin' => Yii::t('models', 'Active4Admin'),
            'mbr_order' => Yii::t('models', 'Order'),
            'mbr_code' => Yii::t('models', 'Code'),
            'mbr_title' => Yii::t('models', 'Title'),
            'mbr_groupnr' => Yii::t('models', 'Groupnr'),
            'mbr_cardbody' => Yii::t('models', 'Cardbody'),
            'mbr_detailbody' => Yii::t('models', 'Detailbody'),
            'mbr_discordroles' => Yii::t('models', 'DiscordRoles'),
						'mbr_discordlogchanid' => Yii::t('models', 'DiscordLogChannelID'),
            'mbr_remarks' => Yii::t('models', 'Remarks'),
            'mbr_lock' => Yii::t('models', 'Lock'),
            'mbr_createdat' => Yii::t('models', 'CreatedAt'),
            'mbr_createdt' => Yii::t('models', 'Created'),
            'mbrusr_created_id' => Yii::t('models', 'CreatedBy'),
            'mbr_updatedat' => Yii::t('models', 'UpdatedAt'),
            'mbr_updatedt' => Yii::t('models', 'Updated'),
            'mbrusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'mbr_deletedat' => Yii::t('models', 'DeletedAt'),
            'mbr_deletedt' => Yii::t('models', 'Deleted'),
            'mbrusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

}

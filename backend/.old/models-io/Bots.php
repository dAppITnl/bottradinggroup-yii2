<?php

namespace backend\models;

use Yii;
use \backend\models\base\Bots as BaseBots;

/**
 * This is the model class for table "bots".
 */
class Bots extends BaseBots
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['bot_lock', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['bot_name', 'bot_3cbotid', 'bot_dealstartjson', 'created_at', 'created_by', 'updated_at', 'updated_by', 'bot_createdt', 'bot_updatedt'], 'required'],
            [['bot_dealstartjson', 'bot_remarks'], 'string'],
            [['bot_createdt', 'bot_updatedt', 'bot_deletedt'], 'safe'],
            [['bot_name', 'bot_3cbotid'], 'string', 'max' => 64],
            [['bot_lock'], 'default', 'value' => '0'],
            [['bot_lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'id' => Yii::t('app', 'BotId'),
            'bot_lock' => Yii::t('app', 'Lock'),
            'bot_name' => Yii::t('app', 'Name'),
            'bot_3cbotid' => Yii::t('app', '3CBotId'),
            'bot_dealstartjson' => Yii::t('app', 'DealStrartJson'),
            'bot_createdt' => Yii::t('app', 'Created'),
            'bot_updatedt' => Yii::t('app', 'Updated'),
            'bot_deletedt' => Yii::t('app', 'Deleted'),
            'bot_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
}

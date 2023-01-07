<?php

namespace backend\models;

use Yii;
use \backend\models\base\Signallogs as BaseSignallogs;

/**
 * This is the model class for table "signallogs".
 */
class Signallogs extends BaseSignallogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['siglog_userId', 'siglog_botId', 'siglog_name', 'siglog_type', 'siglog_message', 'created_at', 'created_by', 'updated_at', 'updated_by', 'siglog_createdt'], 'required'],
            [['siglog_userId', 'siglog_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['siglog_message'], 'string'],
            [['siglog_createdt'], 'safe'],
            [['siglog_name', 'siglog_type'], 'string', 'max' => 32],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'id' => Yii::t('app', 'SiglogId'),
            'siglog_userId' => Yii::t('app', 'UserId'),
            'siglog_botId' => Yii::t('app', 'BotId'),
            'siglog_name' => Yii::t('app', 'Name'),
            'siglog_type' => Yii::t('app', 'Type'),
            'siglog_message' => Yii::t('app', 'Message'),
            'siglog_createdt' => Yii::t('app', 'Created'),
        ];
    }
}

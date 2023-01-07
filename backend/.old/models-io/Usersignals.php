<?php

namespace backend\models;

use Yii;
use \backend\models\base\Usersignals as BaseUsersignals;

/**
 * This is the model class for table "usersignals".
 */
class Usersignals extends BaseUsersignals
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['usrsig_lock', 'usrsig_userId', 'usrsig_botId', 'usrsig_name', 'usrsig_emailtoken', 'usrsig_pair', 'created_at', 'created_by', 'updated_at', 'updated_by', 'usrsig_createdt', 'usrsig_updatedt'], 'required'],
            [['usrsig_lock', 'usrsig_userId', 'usrsig_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrsig_createdt', 'usrsig_updatedt', 'usrsig_deletedt'], 'safe'],
            [['usrsig_remarks'], 'string'],
            [['usrsig_name', 'usrsig_pair'], 'string', 'max' => 32],
            [['usrsig_emailtoken'], 'string', 'max' => 64],
            [['usrsig_lock'], 'default', 'value' => '0'],
            [['usrsig_lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'id' => Yii::t('app', 'UserSignalId'),
            'usrsig_lock' => Yii::t('app', 'Lock'),
            'usrsig_userId' => Yii::t('app', 'UserId'),
            'usrsig_botId' => Yii::t('app', 'BotId'),
            'usrsig_name' => Yii::t('app', 'Name'),
            'usrsig_emailtoken' => Yii::t('app', 'Emailtoken'),
            'usrsig_pair' => Yii::t('app', 'Pair'),
            'usrsig_createdt' => Yii::t('app', 'Created'),
            'usrsig_updatedt' => Yii::t('app', 'Updated'),
            'usrsig_deletedt' => Yii::t('app', 'Deleted'),
            'usrsig_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
}

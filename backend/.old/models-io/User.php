<?php

namespace backend\models;

use Yii;
use \backend\models\base\User as BaseUser;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['username', 'user_password', 'password_hash', 'auth_key', 'email', 'status', 'user_SignalActive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_by', 'user_created', 'user_updated'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['user_created', 'user_updated', 'user_deletedt'], 'safe'],
            [['user_remarks'], 'string'],
            [['username', 'user_password', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'user_moralisId'], 'string', 'max' => 64],
            [['auth_key'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 4],
            [['user_SignalActive'], 'string', 'max' => 1],
            [['username', 'email'], 'unique', 'targetAttribute' => ['username', 'email'], 'message' => 'The combination of Username and Email has already been taken.'],
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
            'id' => Yii::t('app', 'UserID'),
            'username' => Yii::t('app', 'Username'),
            'user_password' => Yii::t('app', 'Password'),
            'password_hash' => Yii::t('app', 'PasswordHash'),
            'password_reset_token' => Yii::t('app', 'PasswordReset'),
            'auth_key' => Yii::t('app', 'Authkey'),
            'email' => Yii::t('app', 'Email'),
            'verification_token' => Yii::t('app', 'VerificationToken'),
            'status' => Yii::t('app', 'Status'),
            'user_SignalActive' => Yii::t('app', 'SignalActive'),
            'user_moralisId' => Yii::t('app', 'MoralisId'),
            'user_created' => Yii::t('app', 'Created'),
            'user_updated' => Yii::t('app', 'Updated'),
            'user_deletedt' => Yii::t('app', 'Deleted'),
            'user_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
}

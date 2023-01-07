<?php

namespace frontend\models;

use Yii;
use \backend\models\User as BackendUser;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 */
class User extends BackendUser
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
            'id' => Yii::t('models', 'ID'),
            'usr_language' => Yii::t('models', 'Language'),
            'status' => Yii::t('models', 'Status'),
            'username' => Yii::t('models', 'Username'),
            'usr_password' => Yii::t('models', 'Password'),
            'usr_2fasecret' => Yii::t('models', '2FASecret'),
            'email' => Yii::t('models', 'Email'),
						'usr_firstname' => Yii::t('models', 'Firstname'),
            'usr_lastname' => Yii::t('models', 'Lastname'),
            'usr_countrycode' => Yii::t('models', 'Countrycode'),
            'usr_sitelevel' => Yii::t('models', 'SiteLevel'),
            'usr_sitecsstheme' => Yii::t('models', 'Site Theme'),
            'usr_signalactive' => Yii::t('models', 'SignalActive'),
            'usr_moralisid' => Yii::t('models', 'MoralisId'),
            'usr_discordusername' => Yii::t('models', 'DiscordUsername'),
            'usr_discordnick' => Yii::t('models', 'DiscordNick'),
            'usr_discordjoinedat' => Yii::t('models', 'DiscordJoinedAt'),
            'usr_discordid' => Yii::t('models', 'DiscordId'),
            'usr_discordroles' => Yii::t('models', 'DiscordRoles'),
            'password_hash' => Yii::t('models', 'PasswordHash'),
            'password_reset_token' => Yii::t('models', 'PasswordReset'),
            'auth_key' => Yii::t('models', 'Authkey'),
            'verification_token' => Yii::t('models', 'VerificationToken'),
            'usr_remarks' => Yii::t('models', 'Remarks'),
            'usr_lock' => Yii::t('models', 'Lock'),
            'created_at' => Yii::t('models', 'CreatedAt'),
            'usr_createdt' => Yii::t('models', 'Created'),
            'created_by' => Yii::t('models', 'CreatedBy'),
            'updated_at' => Yii::t('models', 'UpdatedAt'),
            'usr_updatedt' => Yii::t('models', 'Updated'),
            'updated_by' => Yii::t('models', 'UpdatedBy'),
            'deleted_at' => Yii::t('models', 'DeletedAt'),
            'usr_deletedt' => Yii::t('models', 'Deleted'),
            'deleted_by' => Yii::t('models', 'DeletedBy'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'usr_language' => Yii::t('models', 'Language'),
            'status' => Yii::t('models', 'Status'),
            'username' => Yii::t('models', 'Username'),
            'usr_password' => Yii::t('models', 'Password'),
            'usr_2fasecret' => Yii::t('models', '2FASecret'),
            'email' => Yii::t('models', 'Email'),
				*/    'usr_firstname' => Yii::t('models', 'For (crypto) payments via Utrust'),
            'usr_lastname' => Yii::t('models', 'For (crypto) payments via Utrust'),
            'usr_countrycode' => Yii::t('models', 'For (crypto) payments via Utrust: 2 letters ISO-code (ex.: NL)')
        /*  'usr_sitelevel' => Yii::t('models', 'SiteLevel'),
            'usr_sitecsstheme' => Yii::t('models', 'SiteCssTheme'),
            'usr_signalactive' => Yii::t('models', 'SignalActive'),
            'usr_moralisid' => Yii::t('models', 'MoralisId'),
            'usr_discordusername' => Yii::t('models', 'DiscordUsername'),
            'usr_discordnick' => Yii::t('models', 'DiscordNick'),
            'usr_discordjoinedat' => Yii::t('models', 'DiscordJoinedAt'),
            'usr_discordid' => Yii::t('models', 'DiscordId'),
            'usr_discordroles' => Yii::t('models', 'DiscordRoles'),
            'password_hash' => Yii::t('models', 'PasswordHash'),
            'password_reset_token' => Yii::t('models', 'PasswordReset'),
            'auth_key' => Yii::t('models', 'Authkey'),
            'verification_token' => Yii::t('models', 'VerificationToken'),
            'usr_remarks' => Yii::t('models', 'Remarks'),
            'usr_lock' => Yii::t('models', 'Lock'),
            'created_at' => Yii::t('models', 'CreatedAt'),
            'usr_createdt' => Yii::t('models', 'Created'),
            'created_by' => Yii::t('models', 'CreatedBy'),
            'updated_at' => Yii::t('models', 'UpdatedAt'),
            'usr_updatedt' => Yii::t('models', 'Updated'),
            'updated_by' => Yii::t('models', 'UpdatedBy'),
            'deleted_at' => Yii::t('models', 'DeletedAt'),
            'usr_deletedt' => Yii::t('models', 'Deleted'),
            'deleted_by' => Yii::t('models', 'DeletedBy'),  */
        ]);
    }



}

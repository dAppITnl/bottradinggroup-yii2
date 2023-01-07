<?php
namespace common\helpers;

use Yii;
use common\helpers\GeneralHelper;

/**
 * Extended yii\web\User
 *
 * This allows us to do "Yii::$app->user->something" by adding getters
 * like "public function getSomething()"
 *
 * So we can use variables and functions directly in `Yii::$app->user`
 */
class User extends \yii\web\User
{

		// ***** NOT USED -> use /common/models/User.php !!

    public function getUsername()
    {
        return \Yii::$app->user->identity->username;
    }

    public function getLanguage()
    {
        return \Yii::$app->user->identity->usr_language;
    }

		public function getLanguageName()
		{
			$getLanguages = GeneralHelper::getLanguages();
			$language = $this->getLanguage();
			return (!empty($language) && isset($getLanguages[ $language ])) ? $getLanguages[ $language ] : 'nl-NL';
		}

		public function getMoralisid()
    {
        return \Yii::$app->user->identity->usr_moralisid;
    }

    public function getDiscordid()
    {
        return \Yii::$app->user->identity->usr_discordid;
    }

    public function getEmail()
    {
        return \Yii::$app->user->identity->email;
    }
    public function getRemarks()
    {
        return \Yii::$app->user->identity->usr_remarks;
    }

    public function getCreatedt()
    {
        return \Yii::$app->user->identity->usr_createdt;
    }

}

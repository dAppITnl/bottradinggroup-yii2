<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\User as frontendUser;
use yii\bootstrap4\Html;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
		public $discordid;
		public $discordusername;
		public $discordnick;
		public $discordjoinedat;
		public $discordroles;
		public $accept_disclaimer;
		public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app','This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

						['discordid', 'trim'],
            ['discordid', 'string', 'min' => 0, 'max' => 64],

						[['discordusername', 'discordnick', 'discordjoinedat', 'discordroles'], 'string', 'max' => 64],

						['accept_disclaimer', 'required', 'requiredValue'=>1, 'message'=>Yii::t('app', 'You have to accept the disclaimer')],

						//['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6LedI9gcAAAAADaaHDycu0wsVCL2pmdeWRWbIdQ9'], // v3: '6LfuIdgcAAAAAEMorWaqeUxTN1ZuVB7EIcwuZdr1'],
						['reCaptcha', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'Password' => Yii::t('app', 'Password'),
						'discordid' => Yii::t('app', 'Discord id (optional)'),
						'discordusername' => Yii::t('app', 'Discord username (optional)'),
						'accept_disclaimer' => Yii::t('app', 'Accept the disclaimer'),
            'Re Captcha' => Yii::t('app', 'Check'),
            'Signup' => Yii::t('app', 'Signup'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
					  'username' => Yii::t('app', 'Please, choose carefully, cannot be changed later '),
            'email' => Yii::t('app', 'You will receive an email to this address to confirm'),
            //'Password' => Yii::t('app', 'Password'),
            'discordusername' => Yii::t('app', 'Your discord username or nick (for roles); will be checked immediately'),
						'discordid' => Yii::t('app', 'Your discord ID (for roles), will be checked immediately'),
            'accept_disclaimer' => Yii::t('app', 'Accept the disclaimer'),
            //'Re Captcha' => Yii::t('app', 'Check'),
            //'Signup' => Yii::t('app', 'Signup'),

				]);
		}

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
				$user->usr_sitelevel = frontendUser::USR_SITELEVEL_USER;

				$user->usr_discordid = $this->discordid;
				$user->usr_discordusername = $this->discordusername;
				$user->usr_discordnick = $this->discordnick;
				$user->usr_discordjoinedat = $this->discordjoinedat;
				$user->usr_discordroles = $this->discordroles;

				$user->usr_createdt = $user->usr_updatedt = date('Y-m-d H:i:s', time());

        return $user->save() && $this->sendEmail($user);
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
		 * 8nov21 made public to call it from user update forms too
     */
    /*protected*/public function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo(/*$this*/$user->email)
            ->setSubject(Yii::t('app', 'Account registration at {appName}', ['appName' => Yii::$app->name]))
            ->send();
    }
}

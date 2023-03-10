<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
		public $kind;
    public $subject;
    public $body;
    //public $verifyCode;
    public $reCaptcha;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'kind', 'subject', 'body', 'reCaptcha'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],
						//['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => '6LedI9gcAAAAADaaHDycu0wsVCL2pmdeWRWbIdQ9'], // v3: '6LfuIdgcAAAAAEMorWaqeUxTN1ZuVB7EIcwuZdr1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app', 'Verification Code'),
						'name' => Yii::t('app', 'Name'),
						'email' => Yii::t('app', 'Email'),
						'kind' => Yii::t('app', 'Kind'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'reCaptcha' => Yii::t('app', 'Check'),
            'verifyCode' => Yii::t('app', 'Verification code'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
				$contactKinds = \common\helpers\GeneralHelper::getContactKinds();

        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject('[' . $contactKinds[ $this->kind ] . ']: ' . $this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}

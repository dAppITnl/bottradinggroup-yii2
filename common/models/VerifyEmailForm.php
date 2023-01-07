<?php

namespace common\models;

use common\models\User;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var User
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => Yii::t('common', 'Token'),
        ];
    }

    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
				// improved when already been validated -> no longer thrown, but accepted and login
        //if (empty($token) || !is_string($token)) {
        //    throw new InvalidArgumentException('Verify email token cannot be blank.');
        //}
        $this->_user = ((!empty($token) && is_string($token)) ? User::findByVerificationToken($token) : null); // null if notFound , no longer checked for status==inactive
        //if (!$this->_user) {
        //    throw new InvalidArgumentException('Wrong verify email token.');
        //}
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return User|null the saved model or null if saving fails
     */
    public function verifyEmail()
    {
				$ok = !is_null($this->_user);
				if ($ok) {
        	$user = $this->_user;
					if ($user->status == User::STATUS_INACTIVE) {
						$user->status = User::STATUS_ACTIVE;
		        $user->updated_at = time();
						$user->updated_by = (!empty(\Yii::$app->user->id) ? \Yii::$app->user->id : 1);
						$user->usr_updatedt = date('Y-m-d H:i:s', time());
						$ok = $user->save(false);
					}
				}
				return ($ok ? $user : null);
    }
}

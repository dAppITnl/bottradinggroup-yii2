<?php

namespace common\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use Yii;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
		public $password2;

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('common', 'Password'),
						'password2' => Yii::t('common', 'Repeat password'),
        ];
    }

    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
				$_user = null;
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException(Yii::t('common', 'Password reset token cannot be blank.'));
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException(Yii::t('common', 'Wrong password reset token.'));
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password2'], 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
						['password', 'compare', 'compareAttribute' => 'password2', 'operator' => '==',
							'message' => Yii::t('common', 'Passwords are not equal, please re enter')],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
      $user = $this->_user;
      $user->setPassword($this->password);
      $user->removePasswordResetToken();
      $user->generateAuthKey();

      $user->updated_at = time();
      $user->updated_by = (!empty(\Yii::$app->user->id) ? \Yii::$app->user->id : 1);
      $user->usr_updatedt = date('Y-m-d H:i:s', time());

      return $user->save(false);
    }
}

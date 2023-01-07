<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\bootstrap4\Html;
use common\helpers\GeneralHelper;

/**
 * Login form
 */
class LoginForm extends Model
{
  public $username;
  public $password;
	public $check2fa;
	public $usr_moralisid;
  public $rememberMe = true;

  private $_user;


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      // username and password are both required
      [['username', 'password', 'check2fa'], 'required'],
      // rememberMe must be a boolean value
      ['rememberMe', 'boolean'],
      // password is validated by validatePassword()
      ['password', 'validatePassword'],
			// validate check2fa
			['check2fa', 'validate2FA'],
			// moralisId
			['usr_moralisid', 'string'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'username' => Yii::t('common', 'Username'),
      'password' => Yii::t('common', 'Password'),
      'RememberMe' => Yii::t('common', 'Remember me'),
			'check2fa' => Yii::t('common', '2FA code'),
    ];
  }

  /**
   * Validates the password.
   * This method serves as the inline validation for password.
   *
   * @param string $attribute the attribute currently being validated
   * @param array $params the additional name-value pairs given in the rule
   */
  public function validatePassword($attribute, $params)
  {
    if (!$this->hasErrors()) {
      $user = $this->getUser();
      if (!$user || !$user->validatePassword($this->password)) {
				if ($user->status!=10) {
					$this->addError($attribute, Yii::t('common', 'Please validate your email address then you can login'));
				} else {
					$this->addError($attribute, Yii::t('common', 'Incorrect username or password.'));
				}
      }
    }
  }

	public function validate2FA($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			$ok = false;
			if ($user) {
      	//Yii::trace('** validate2FA 2fasecret='.$user->usr_2fasecret);
      	if (!empty($user->usr_2fasecret)) {
        	$google2fa = new \PragmaRX\Google2FAQRCode\Google2FA();
        	//Yii::trace('** validate2FA check2fa='.$this->check2fa);
        	$ok = $google2fa->verifyKey($user->usr_2fasecret, $this->check2fa);
				} else $ok = true;
			}
     	if (!$ok) $this->addError($attribute, Yii::trace('app', 'Incorrect username or 2FA code.'));
		}
	}

  /**
   * Logs in a user using the provided username and password.
   *
   * @return bool whether the user is logged in successfully
   */
  public function login()
  {
    if ($this->validate()) {
			$user = $this->getUser();
			$result = Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
			Yii::trace('** LoginForm login moralisID='.$this->usr_moralisid.' = '.$user->usr_moralisid);
			if ($result && !empty($user->id) && !empty($this->usr_moralisid) && ($this->usr_moralisid != $user->usr_moralisid)) {
				$sql = "UPDATE user SET usr_moralisid='".$this->usr_moralisid."', updated_at='".time()."', usr_updatedt=NOW() WHERE id=".$user->id;
				$sqlresult = GeneralHelper::runSql($sql);
				Yii::trace('** LoginForm login sql='.$sql.' => '.$sqlresult);
			}
			return $result;
    }
    return false;
  }

	public function check42fa()
	{
		$result = false;
		Yii::trace('model check2fa username='.$this->username);
		if (!empty($this->username)) {
			$user = $this->getUser();
			Yii::trace('model check2fa usr_2fasecret='.$user->usr_2fasecret);
			$result = (!empty($user->usr_2fasecret));
		}
		return $result;
	}

  /**
   * Finds user by [[username]]
   *
   * @return User|null
   */
  protected function getUser()
  {
    if ($this->_user === null) {
      $this->_user = User::findByUsername($this->username);
    }
    return $this->_user;
  }
}

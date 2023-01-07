<?php

namespace backend\models;

use Yii;
use \backend\models\base\User as BaseUser;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{
	public $_inlineurl2fa, $_authenticatorreply;

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
								[['_inlineurl2fa', '_authenticatorreply'], 'string'],
            ]
        );
    }

  public function getInlineurl2fa()
  {
    return $this->_inlineurl2fa;
  }

  public function setInlineurl2fa($inlineurl2fa)
  {
    return $this->_inlineurl2fa = $inlineurl2fa;
  }

  public function getAuthenticatorreply()
  {
    return $this->_authenticatorreply;
  }

  public function setAuthenticatorreply($authenticatorreply)
  {
    return $this->_authenticatorreply = $authenticatorreply;
  }

// --------------------

  public function getSitelevelKeysDown($fromLevel=self::USR_SITELEVEL_DEV, $lastLevel=self::USR_SITELEVEL_NONE) {
    $result = [];
    switch ( $fromLevel ) {
      case self::USR_SITELEVEL_DEV:        $result[] = self::USR_SITELEVEL_DEV;        if ($lastLevel==self::USR_SITELEVEL_DEV) break;
      case self::USR_SITELEVEL_SUPERADMIN: $result[] = self::USR_SITELEVEL_SUPERADMIN; if ($lastLevel==self::USR_SITELEVEL_SUPERADMIN) break;
      case self::USR_SITELEVEL_ADMIN:      $result[] = self::USR_SITELEVEL_ADMIN;      if ($lastLevel==self::USR_SITELEVEL_ADMIN) break;
      case self::USR_SITELEVEL_SUPPORT:    $result[] = self::USR_SITELEVEL_SUPPORT;    if ($lastLevel==self::USR_SITELEVEL_SUPPORT) break;
			case self::USR_SITELEVEL_BTESTER:    $result[] = self::USR_SITELEVEL_BTESTER;    if ($lastLevel==self::USR_SITELEVEL_BTESTER) break;
			case self::USR_SITELEVEL_ATESTER:    $result[] = self::USR_SITELEVEL_ATESTER;    if ($lastLevel==self::USR_SITELEVEL_ATESTER) break;
      case self::USR_SITELEVEL_MEMBER:     $result[] = self::USR_SITELEVEL_MEMBER;     if ($lastLevel==self::USR_SITELEVEL_MEMBER) break;
			case self::USR_SITELEVEL_HFTMEMBER:  $result[] = self::USR_SITELEVEL_HFTMEMBER;  if ($lastLevel==self::USR_SITELEVEL_HFTMEMBER) break;
      case self::USR_SITELEVEL_USER:       $result[] = self::USR_SITELEVEL_USER;       if ($lastLevel==self::USR_SITELEVEL_USER) break;
      case self::USR_SITELEVEL_GUEST:      $result[] = self::USR_SITELEVEL_GUEST;      if ($lastLevel==self::USR_SITELEVEL_GUEST) break;
      default:
      case self::USR_SITELEVEL_NONE:       $result[] = self::USR_SITELEVEL_NONE;
    }
    return $result;
  }

  public function getSitelevelKeysUp($fromLevel=self::USR_SITELEVEL_NONE, $lastLevel=self::USR_SITELEVEL_DEV) {
    $result = [];
    switch ( $fromLevel ) {
      case self::USR_SITELEVEL_NONE:       $result[] = self::USR_SITELEVEL_NONE;       if ($lastLevel==self::USR_SITELEVEL_NONE) break;
      case self::USR_SITELEVEL_GUEST:      $result[] = self::USR_SITELEVEL_GUEST;      if ($lastLevel==self::USR_SITELEVEL_GUEST) break;
      case self::USR_SITELEVEL_USER:       $result[] = self::USR_SITELEVEL_USER;       if ($lastLevel==self::USR_SITELEVEL_USER) break;
			case self::USR_SITELEVEL_HFTMEMBER:  $result[] = self::USR_SITELEVEL_HFTMEMBER;  if ($lastLevel==self::USR_SITELEVEL_HFTMEMBER) break;
      case self::USR_SITELEVEL_MEMBER:     $result[] = self::USR_SITELEVEL_MEMBER;     if ($lastLevel==self::USR_SITELEVEL_MEMBER) break;
			case self::USR_SITELEVEL_ATESTER:    $result[] = self::USR_SITELEVEL_ATESTER;    if ($lastLevel==self::USR_SITELEVEL_ATESTER) break;
			case self::USR_SITELEVEL_BTESTER:    $result[] = self::USR_SITELEVEL_BTESTER;    if ($lastLevel==self::USR_SITELEVEL_BTESTER) break;
      case self::USR_SITELEVEL_SUPPORT:    $result[] = self::USR_SITELEVEL_SUPPORT;    if ($lastLevel==self::USR_SITELEVEL_SUPPORT) break;
      case self::USR_SITELEVEL_ADMIN:      $result[] = self::USR_SITELEVEL_ADMIN;      if ($lastLevel==self::USR_SITELEVEL_ADMIN) break;
      case self::USR_SITELEVEL_SUPERADMIN: $result[] = self::USR_SITELEVEL_SUPERADMIN; if ($lastLevel==self::USR_SITELEVEL_SUPERADMIN) break;
      default:
      case self::USR_SITELEVEL_DEV:        $result[] = self::USR_SITELEVEL_DEV;
    }
    return $result;
  }

	public function getUsersWithinLevelsUp($fromLevel=self::USR_SITELEVEL_NONE, $lastLevel=USR_SITELEVEL_DEV, $withLevel=true) {
		$result = [];
		try {
			$userLevels = implode("','", self::getSitelevelKeysUp($fromLevel, $lastLevel));
			$sql = "SELECT id, ".($withLevel ? "CONCAT(usr_sitelevel,': ',username) as " : "")."username FROM user WHERE usr_sitelevel IN ('".$userLevels."') AND deleted_at=0";
			$rows = GeneralHelper::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr)) $result[ $row['id'] ] = $row['username'];
		} catch(\Exception $e) {
      $msg = 'Error: '.$e->getMessage();
      Yii::trace('** getUsersWithinLevels msg='.$msg );
      throw new \Exception($msg);
    }
		return $result;
	}
}

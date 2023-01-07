<?php

namespace apiv1\models;

use Yii;
use backend\models\User as BackendUser;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "userpay".
 */
class User extends BackendUser
{
	public function fields()
	{
		return ['id', 'username', 'email'];
	}

	public function extraFields()
	{
		return [];
	}
}

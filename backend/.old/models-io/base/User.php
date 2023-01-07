<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $user_password
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $email
 * @property string $verification_token
 * @property integer $status
 * @property integer $user_SignalActive
 * @property string $user_moralisId
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $user_created
 * @property string $user_updated
 * @property string $user_deletedt
 * @property string $user_remarks
 *
 * @property \backend\models\Signallogs[] $signallogs
 * @property \backend\models\Userpaids[] $userpaids
 * @property \backend\models\Usersignals[] $usersignals
 */
class User extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    private $_rt_softdelete;
    private $_rt_softrestore;

    public function __construct(){
        parent::__construct();
        $this->_rt_softdelete = [
            'deleted_by' => \Yii::$app->user->id,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
        $this->_rt_softrestore = [
            'deleted_by' => 0,
            'deleted_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'signallogs',
            'userpaids',
            'usersignals'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSignallogs()
    {
        return $this->hasMany(\backend\models\Signallogs::className(), ['siglog_userId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserpaids()
    {
        return $this->hasMany(\backend\models\Userpaids::className(), ['usrpay_userId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersignals()
    {
        return $this->hasMany(\backend\models\Usersignals::className(), ['usrsig_userId' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'uuid' => [
                'class' => UUIDBehavior::className(),
                'column' => 'id',
            ],
        ];
    }

    /**
     * The following code shows how to apply a default condition for all queries:
     *
     * ```php
     * class Customer extends ActiveRecord
     * {
     *     public static function find()
     *     {
     *         return parent::find()->where(['deleted' => false]);
     *     }
     * }
     *
     * // Use andWhere()/orWhere() to apply the default condition
     * // SELECT FROM customer WHERE `deleted`=:deleted AND age>30
     * $customers = Customer::find()->andWhere('age>30')->all();
     *
     * // Use where() to ignore the default condition
     * // SELECT FROM customer WHERE age>30
     * $customers = Customer::find()->where('age>30')->all();
     * ```
     */

    /**
     * @inheritdoc
     * @return \backend\models\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \backend\models\UserQuery(get_called_class());
        return $query->where(['user.deleted_by' => 0]);
    }
}

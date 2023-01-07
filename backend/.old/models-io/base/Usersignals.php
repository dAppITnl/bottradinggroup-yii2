<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "usersignals".
 *
 * @property integer $id
 * @property integer $usrsig_lock
 * @property integer $usrsig_userId
 * @property integer $usrsig_botId
 * @property string $usrsig_name
 * @property string $usrsig_emailtoken
 * @property string $usrsig_pair
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $usrsig_createdt
 * @property string $usrsig_updatedt
 * @property string $usrsig_deletedt
 * @property string $usrsig_remarks
 *
 * @property \backend\models\Bots $usrsigBot
 * @property \backend\models\User $usrsigUser
 */
class Usersignals extends \yii\db\ActiveRecord
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
            'usrsigBot',
            'usrsigUser'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usrsig_lock', 'usrsig_userId', 'usrsig_botId', 'usrsig_name', 'usrsig_emailtoken', 'usrsig_pair', 'created_at', 'created_by', 'updated_at', 'updated_by', 'usrsig_createdt', 'usrsig_updatedt'], 'required'],
            [['usrsig_lock', 'usrsig_userId', 'usrsig_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrsig_createdt', 'usrsig_updatedt', 'usrsig_deletedt'], 'safe'],
            [['usrsig_remarks'], 'string'],
            [['usrsig_name', 'usrsig_pair'], 'string', 'max' => 32],
            [['usrsig_emailtoken'], 'string', 'max' => 64],
            [['usrsig_lock'], 'default', 'value' => '0'],
            [['usrsig_lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usersignals';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'usrsig_lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'UserSignalId'),
            'usrsig_lock' => Yii::t('app', 'Lock'),
            'usrsig_userId' => Yii::t('app', 'UserId'),
            'usrsig_botId' => Yii::t('app', 'BotId'),
            'usrsig_name' => Yii::t('app', 'Name'),
            'usrsig_emailtoken' => Yii::t('app', 'Emailtoken'),
            'usrsig_pair' => Yii::t('app', 'Pair'),
            'usrsig_createdt' => Yii::t('app', 'Created'),
            'usrsig_updatedt' => Yii::t('app', 'Updated'),
            'usrsig_deletedt' => Yii::t('app', 'Deleted'),
            'usrsig_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsrsigBot()
    {
        return $this->hasOne(\backend\models\Bots::className(), ['id' => 'usrsig_botId']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsrsigUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'usrsig_userId']);
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
     * @return \backend\models\UsersignalsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \backend\models\UsersignalsQuery(get_called_class());
        return $query->where(['usersignals.deleted_by' => 0]);
    }
}

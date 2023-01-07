<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "signallogs".
 *
 * @property integer $id
 * @property integer $siglog_userId
 * @property integer $siglog_botId
 * @property string $siglog_name
 * @property string $siglog_type
 * @property string $siglog_message
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $siglog_createdt
 *
 * @property \backend\models\Bots $siglogBot
 * @property \backend\models\User $siglogUser
 */
class Signallogs extends \yii\db\ActiveRecord
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
            'siglogBot',
            'siglogUser'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['siglog_userId', 'siglog_botId', 'siglog_name', 'siglog_type', 'siglog_message', 'created_at', 'created_by', 'updated_at', 'updated_by', 'siglog_createdt'], 'required'],
            [['siglog_userId', 'siglog_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['siglog_message'], 'string'],
            [['siglog_createdt'], 'safe'],
            [['siglog_name', 'siglog_type'], 'string', 'max' => 32],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'signallogs';
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
            'id' => Yii::t('app', 'SiglogId'),
            'siglog_userId' => Yii::t('app', 'UserId'),
            'siglog_botId' => Yii::t('app', 'BotId'),
            'siglog_name' => Yii::t('app', 'Name'),
            'siglog_type' => Yii::t('app', 'Type'),
            'siglog_message' => Yii::t('app', 'Message'),
            'siglog_createdt' => Yii::t('app', 'Created'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiglogBot()
    {
        return $this->hasOne(\backend\models\Bots::className(), ['id' => 'siglog_botId']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiglogUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'siglog_userId']);
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
     * @return \backend\models\SignallogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \backend\models\SignallogsQuery(get_called_class());
        return $query->where(['signallogs.deleted_by' => 0]);
    }
}

<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "bots".
 *
 * @property integer $id
 * @property integer $bot_lock
 * @property string $bot_name
 * @property string $bot_3cbotid
 * @property string $bot_dealstartjson
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $bot_createdt
 * @property string $bot_updatedt
 * @property string $bot_deletedt
 * @property string $bot_remarks
 *
 * @property \backend\models\Signallogs[] $signallogs
 * @property \backend\models\Usersignals[] $usersignals
 */
class Bots extends \yii\db\ActiveRecord
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
            'usersignals'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bot_lock', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['bot_name', 'bot_3cbotid', 'bot_dealstartjson', 'created_at', 'created_by', 'updated_at', 'updated_by', 'bot_createdt', 'bot_updatedt'], 'required'],
            [['bot_dealstartjson', 'bot_remarks'], 'string'],
            [['bot_createdt', 'bot_updatedt', 'bot_deletedt'], 'safe'],
            [['bot_name', 'bot_3cbotid'], 'string', 'max' => 64],
            [['bot_lock'], 'default', 'value' => '0'],
            [['bot_lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bots';
    }

    /**
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'bot_lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'BotId'),
            'bot_lock' => Yii::t('app', 'Lock'),
            'bot_name' => Yii::t('app', 'Name'),
            'bot_3cbotid' => Yii::t('app', '3CBotId'),
            'bot_dealstartjson' => Yii::t('app', 'DealStrartJson'),
            'bot_createdt' => Yii::t('app', 'Created'),
            'bot_updatedt' => Yii::t('app', 'Updated'),
            'bot_deletedt' => Yii::t('app', 'Deleted'),
            'bot_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSignallogs()
    {
        return $this->hasMany(\backend\models\Signallogs::className(), ['siglog_botId' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsersignals()
    {
        return $this->hasMany(\backend\models\Usersignals::className(), ['usrsig_botId' => 'id']);
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
     * @return \backend\models\BotsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \backend\models\BotsQuery(get_called_class());
        return $query->where(['bots.deleted_by' => 0]);
    }
}

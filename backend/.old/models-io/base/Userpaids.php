<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * This is the base model class for table "userpaids".
 *
 * @property integer $id
 * @property string $usrpay_name
 * @property integer $usrpay_userId
 * @property string $usrpay_startdt
 * @property string $usrpay_enddt
 * @property string $usrpay_payamount
 * @property string $usrpay_paysymbol
 * @property string $usrpay_rate
 * @property string $usrpay_ratesymbol
 * @property string $usrpay_paiddt
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property integer $deleted_at
 * @property integer $deleted_by
 * @property string $usrpay_createdt
 * @property string $usrpay_updatedt
 * @property string $usrpay_deletedt
 * @property string $usrpay_remarks
 *
 * @property \backend\models\User $usrpayUser
 */
class Userpaids extends \yii\db\ActiveRecord
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
            'usrpayUser'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usrpay_name', 'usrpay_userId', 'usrpay_startdt', 'usrpay_payamount', 'usrpay_paysymbol', 'usrpay_rate', 'usrpay_ratesymbol', 'created_at', 'created_by', 'updated_at', 'updated_by', 'usrpay_createdt', 'usrpay_updatedt'], 'required'],
            [['usrpay_userId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrpay_startdt', 'usrpay_enddt', 'usrpay_paiddt', 'usrpay_createdt', 'usrpay_updatedt', 'usrpay_deletedt'], 'safe'],
            [['usrpay_remarks'], 'string'],
            [['usrpay_name', 'usrpay_payamount', 'usrpay_paysymbol', 'usrpay_rate', 'usrpay_ratesymbol'], 'string', 'max' => 32],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userpaids';
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
            'id' => Yii::t('app', 'UserpayId'),
            'usrpay_name' => Yii::t('app', 'Name'),
            'usrpay_userId' => Yii::t('app', 'UserId'),
            'usrpay_startdt' => Yii::t('app', 'StartDt'),
            'usrpay_enddt' => Yii::t('app', 'EndDt'),
            'usrpay_payamount' => Yii::t('app', 'Payamount'),
            'usrpay_paysymbol' => Yii::t('app', 'Paysymbol'),
            'usrpay_rate' => Yii::t('app', 'Rate'),
            'usrpay_ratesymbol' => Yii::t('app', 'Ratesymbol'),
            'usrpay_paiddt' => Yii::t('app', 'PaidDt'),
            'usrpay_createdt' => Yii::t('app', 'Created'),
            'usrpay_updatedt' => Yii::t('app', 'Updated'),
            'usrpay_deletedt' => Yii::t('app', 'Deleted'),
            'usrpay_remarks' => Yii::t('app', 'Remarks'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsrpayUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'usrpay_userId']);
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
     * @return \backend\models\UserpaidsQuery the active query used by this AR class.
     */
    public static function find()
    {
        $query = new \backend\models\UserpaidsQuery(get_called_class());
        return $query->where(['userpaids.deleted_by' => 0]);
    }
}

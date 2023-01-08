<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "userpay".
 *
 * @property integer $id
 * @property integer $upyusr_id
 * @property integer $upyumb_id
 * @property integer $upymbr_id
 * @property integer $upycat_id
 * @property integer $upyprl_id
 * @property string $upy_state
 * @property string $upy_name
 * @property integer $upy_maxsignals
 * @property string $upy_percode
 * @property string $upy_startdate
 * @property string $upy_enddate
 * @property string $upy_discountcode
 * @property double $upy_payamount
 * @property double $upy_cryptoamount
 * @property integer $upysym_pay_id
 * @property integer $upysym_crypto_id
 * @property string $upy_rate
 * @property integer $upysym_rate_id
 * @property string $upy_payprovider
 * @property string $upy_providermode
 * @property string $upy_providername
 * @property integer $upycad_to_id
 * @property string $upy_toaccount
 * @property string $upy_payref
 * @property string $upy_providerid
 * @property string $upy_payreply
 * @property string $upy_fromaccount
 * @property string $upy_paiddt
 * @property string $upy_remarks
 * @property integer $upy_lock
 * @property string $upy_createdt
 * @property integer $upyusr_created_id
 * @property string $upy_updatedt
 * @property integer $upyusr_updated_id
 * @property integer $upy_deletedat
 * @property string $upy_deletedt
 * @property integer $upyusr_deleted_id
 * @property integer $upy_createdat
 * @property integer $upy_updatedat
 *
 * @property \backend\models\Cryptoaddress $upycadTo
 * @property \backend\models\Category $upycat
 * @property \backend\models\Membership $upymbr
 * @property \backend\models\Pricelist $upyprl
 * @property \backend\models\Symbol $upysymCrypto
 * @property \backend\models\Symbol $upysymPay
 * @property \backend\models\Symbol $upysymRate
 * @property \backend\models\Usermember $upyumb
 * @property \backend\models\User $upyusr
 * @property \backend\models\User $upyusrCreated
 * @property \backend\models\User $upyusrDeleted
 * @property \backend\models\User $upyusrUpdated
 * @property \backend\models\Usermember[] $usermembers
 * @property string $aliasModel
 */
abstract class Userpay extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    const UPY_STATE_CART = 'cart';
    const UPY_STATE_PAYING = 'paying';
    const UPY_STATE_PAID = 'paid';
    const UPY_STATE_CANCEL = 'cancel';
    const UPY_STATE_REJECT = 'reject';
    const UPY_STATE_UNKNOWN = 'unknown';
		const UPY_STATE_OPEN = 'open';
		const UPY_STATE_PENDING = 'pending';
		const UPY_STATE_FAILED = 'failed';
		const UPY_STATE_EXPIRED = 'expired';
		const UPY_STATE_HASREFUNDS = 'hasrefunds';
		const UPY_STATE_HASCHARGEBACKS = 'haschargebacks';

/*    const UPY_PROVIDERTYPE_PAYPAL = 'paypal';
    const UPY_PROVIDERTYPE_FIATBANK = 'fiatbank';
    const UPY_PROVIDERTYPE_MORALIS = 'moralis';
    const UPY_PROVIDERTYPE_CRYPTOBANK = 'cryptobank';
    const UPY_PROVIDERTYPE_CRYPTODIRECT = 'cryptodirect';
		const UPY_PROVIDERTYPE_CRYPTOUTRUST = 'utrust';
		const UPY_PROVIDERTYPE_MOLLIE = 'mollie';
		const UPY_PROVIDERTYPE_NONE = 'none';
    const UPY_PROVIDERTYPE_OTHER = 'other';
*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userpay';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'upy_createdat',
                'updatedAtAttribute' => 'upy_updatedat',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['upyusr_id', 'upyumb_id', 'upy_payamount', 'upy_payprovider', 'upy_createdt', 'upyusr_created_id', 'upy_updatedt', 'upyusr_updated_id'], 'required'],
            [['upyusr_id', 'upyumb_id', 'upymbr_id', 'upycat_id', 'upyprl_id', 'upy_maxsignals', 'upysym_pay_id', 'upysym_crypto_id', 'upysym_rate_id', /*'upycad_to_id',*/ 'upy_lock', 'upyusr_created_id', 'upyusr_updated_id', 'upy_deletedat', 'upyusr_deleted_id'], 'integer'],
            [['upy_state', 'upy_payref', 'upy_payreply', 'upy_remarks'], 'string'],
            [['upycad_to_id', 'upy_startdate', 'upy_enddate', 'upy_paiddt', 'upy_createdt', 'upy_updatedt', 'upy_deletedt'], 'safe'],
            [['upy_payamount', 'upy_cryptoamount'], 'number'],
            [['upy_name', 'upy_rate', 'upy_providermode'], 'string', 'max' => 32],
            [['upy_percode'], 'string', 'max' => 4],
            [['upy_discountcode', 'upy_providername', 'upy_toaccount', 'upy_fromaccount'], 'string', 'max' => 64],
						[['upy_payprovider'], 'string', 'max' => 16],
						[['upy_providerid'], 'string', 'max' => 128],
						[['upycad_to_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Cryptoaddress::className(), 'targetAttribute' => ['upycad_to_id' => 'id']],
            [['upycat_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Category::className(), 'targetAttribute' => ['upycat_id' => 'id']],
            [['upymbr_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Membership::className(), 'targetAttribute' => ['upymbr_id' => 'id']],
						[['upyprl_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Pricelist::className(), 'targetAttribute' => ['upyprl_id' => 'id']],
						[['upysym_crypto_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Symbol::className(), 'targetAttribute' => ['upysym_crypto_id' => 'id']],
            [['upysym_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Symbol::className(), 'targetAttribute' => ['upysym_pay_id' => 'id']],
            [['upysym_rate_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Symbol::className(), 'targetAttribute' => ['upysym_rate_id' => 'id']],
            [['upyumb_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Usermember::className(), 'targetAttribute' => ['upyumb_id' => 'id']],
						[['upyusr_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['upyusr_id' => 'id']],
            [['upyusr_created_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['upyusr_created_id' => 'id']],
            [['upyusr_deleted_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['upyusr_deleted_id' => 'id']],
            [['upyusr_updated_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['upyusr_updated_id' => 'id']],
            ['upy_state', 'in', 'range' => [
                    self::UPY_STATE_CART,
                    self::UPY_STATE_PAYING,
                    self::UPY_STATE_PAID,
                    self::UPY_STATE_CANCEL,
                    self::UPY_STATE_REJECT,
                    self::UPY_STATE_UNKNOWN,
										self::UPY_STATE_OPEN,
										self::UPY_STATE_PENDING,
		                self::UPY_STATE_FAILED,
		                self::UPY_STATE_EXPIRED,
		                self::UPY_STATE_HASREFUNDS,
		                self::UPY_STATE_HASCHARGEBACKS
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
						'upyusr_id' => Yii::t('models', 'User'),
            'upyumb_id' => Yii::t('models', 'Usermember'),
            'upymbr_id' => Yii::t('models', 'Membership'),
            'upycat_id' => Yii::t('models', 'Category'),
						'upyprl_id' => Yii::t('models', 'Pricelist'),
            'upy_state' => Yii::t('models', 'Status'),
            'upy_name' => Yii::t('models', 'Name'),
						'upy_maxsignals' => Yii::t('models', 'MaxSignals'),
            'upy_percode' => Yii::t('models', 'Periodcode'),
            'upy_startdate' => Yii::t('models', 'Startdate'),
            'upy_enddate' => Yii::t('models', 'Enddate'),
						'upy_discountcode' => Yii::t('models', 'Discountcode'),
            'upy_payamount' => Yii::t('models', 'Payamount FIAT'),
						'upy_cryptoamount' => Yii::t('models', 'CryptoAmount'),
            'upysym_pay_id' => Yii::t('models', 'Paysymbol FIAT'),
						'upysym_crypto_id' => Yii::t('models', 'CryptoSymbol'),
            'upy_rate' => Yii::t('models', 'Rate'),
            'upysym_rate_id' => Yii::t('models', 'Ratesymbol'),
            'upy_payprovider' => Yii::t('models', 'PayProvider'),
						'upy_providermode' => Yii::t('models', 'ProviderMode'),
            'upy_providername' => Yii::t('models', 'Providername'),
						'upycad_to_id' => Yii::t('models', 'CryptoToAddress'),
            'upy_toaccount' => Yii::t('models', 'Toaccount'),
            'upy_payref' => Yii::t('models', 'Payref'),
						'upy_providerid' => Yii::t('models', 'Providerid'),
            'upy_payreply' => Yii::t('models', 'Payreply'),
            'upy_fromaccount' => Yii::t('models', 'Fromaccount'),
            'upy_paiddt' => Yii::t('models', 'PaidTime'),
            'upy_remarks' => Yii::t('models', 'Remarks'),
            'upy_lock' => Yii::t('models', 'Lock'),
            'upy_createdat' => Yii::t('models', 'CreatedAt'),
            'upy_createdt' => Yii::t('models', 'Created'),
            'upyusr_created_id' => Yii::t('models', 'CreatedBy'),
            'upy_updatedat' => Yii::t('models', 'UpdatedAt'),
            'upy_updatedt' => Yii::t('models', 'Updated'),
            'upyusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'upy_deletedat' => Yii::t('models', 'DeletedAt'),
            'upy_deletedt' => Yii::t('models', 'Deleted'),
            'upyusr_deleted_id' => Yii::t('models', 'DeletedBy'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
						'upyusr_id' => Yii::t('models', 'User'),
            'upyumb_id' => Yii::t('models', 'Usermember'),
            'upymbr_id' => Yii::t('models', 'Membership'),
            'upycat_id' => Yii::t('models', 'Category'),
						'upyprl_id' => Yii::t('models', 'Pricelist'),
            'upy_state' => Yii::t('models', 'Status'),
            'upy_name' => Yii::t('models', 'Name'),
						'upy_maxsignals' => Yii::t('models', 'MaxSignals'),
            'upy_percode' => Yii::t('models', 'Periodcode'),
            'upy_startdate' => Yii::t('models', 'Startdate'),
            'upy_enddate' => Yii::t('models', 'Enddate'),
						'upy_discountcode' => Yii::t('models', 'Discountcode'),
            'upy_payamount' => Yii::t('models', 'Payamount'),
						'upy_cryptoamount' => Yii::t('models', 'CryptoAmount'),
            'upysym_pay_id' => Yii::t('models', 'Paysymbol'),
						'upysym_crypto_id' => Yii::t('models', 'CryptoSymbol'),
            'upy_rate' => Yii::t('models', 'Rate'),
            'upysym_rate_id' => Yii::t('models', 'Ratesymbol'),
            'upy_payprovider' => Yii::t('models', 'PayProvider'),
						'upy_providermode' => Yii::t('models', 'ProviderMode'),
            'upy_providername' => Yii::t('models', 'Providername'),
						'upycad_to_id' => Yii::t('models', 'CryptoToAddress'),
            'upy_toaccount' => Yii::t('models', 'Toaccount'),
            'upy_payref' => Yii::t('models', 'Payref'),
						'upy_providerid' => Yii::t('models', 'Providerid'),
            'upy_payreply' => Yii::t('models', 'Payreply'),
            'upy_fromaccount' => Yii::t('models', 'Fromaccount'),
            'upy_paiddt' => Yii::t('models', 'PaidTime'),
            'upy_remarks' => Yii::t('models', 'Remarks'),
            'upy_lock' => Yii::t('models', 'Lock'),
            'upy_createdat' => Yii::t('models', 'CreatedAt'),
            'upy_createdt' => Yii::t('models', 'Created'),
            'upyusr_created_id' => Yii::t('models', 'CreatedBy'),
            'upy_updatedat' => Yii::t('models', 'UpdatedAt'),
            'upy_updatedt' => Yii::t('models', 'Updated'),
            'upyusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'upy_deletedat' => Yii::t('models', 'DeletedAt'),
            'upy_deletedt' => Yii::t('models', 'Deleted'),
            'upyusr_deleted_id' => Yii::t('models', 'DeletedBy'), */
        ]);
    }

   /**
   * @return \yii\db\ActiveQuery
   */
   public function getUpycadTo()
   {
       return $this->hasOne(\backend\models\Cryptoaddress::className(), ['id' => 'upycad_to_id']);
   }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpycat()
    {
        return $this->hasOne(\backend\models\Category::className(), ['id' => 'upycat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpymbr()
    {
        return $this->hasOne(\backend\models\Membership::className(), ['id' => 'upymbr_id']);
    }

		/**
		 * @return \yii\db\ActiveQuery
		 */
		public function getUpyprl()
		{
		    return $this->hasOne(\backend\models\Pricelist::className(), ['id' => 'upyprl_id']);
		}

 	  /**
		 * @return \yii\db\ActiveQuery
		 */
		public function getUpysymCrypto()
		{
		    return $this->hasOne(\backend\models\Symbol::className(), ['id' => 'upysym_crypto_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpysymPay()
    {
        return $this->hasOne(\backend\models\Symbol::className(), ['id' => 'upysym_pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpysymRate()
    {
        return $this->hasOne(\backend\models\Symbol::className(), ['id' => 'upysym_rate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpyumb()
    {
        return $this->hasOne(\backend\models\Usermember::className(), ['id' => 'upyumb_id']);
    }

    /**
		 * @return \yii\db\ActiveQuery
		 */
	  public function getUpyusr()
		{
		    return $this->hasOne(\backend\models\User::className(), ['id' => 'upyusr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpyusrCreated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'upyusr_created_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpyusrDeleted()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'upyusr_deleted_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpyusrUpdated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'upyusr_updated_id']);
    }

    /**
		 * @return \yii\db\ActiveQuery
		 */
		public function getUsermembers()
		{
		    return $this->hasMany(\backend\models\Usermember::className(), ['umbupy_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\UserpayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\UserpayQuery(get_called_class());
    }


    /**
     * get column upy_state enum value label
     * @param string $value
     * @return string
     */
    public static function getUpyStateValueLabel($value){
        $labels = self::optsUpyState();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column upy_state ENUM value labels
     * @return array
     */
    public static function optsUpyState()
    {
        return [
            self::UPY_STATE_CART => Yii::t('models', 'Cart'),
            self::UPY_STATE_PAYING => Yii::t('models', 'Paying'),
            self::UPY_STATE_PAID => Yii::t('models', 'Paid'),
            self::UPY_STATE_CANCEL => Yii::t('models', 'Cancel'),
            self::UPY_STATE_REJECT => Yii::t('models', 'Reject'),
            self::UPY_STATE_UNKNOWN => Yii::t('models', 'Unknown'),
						self::UPY_STATE_OPEN => Yii::t('models', 'Open'),
            self::UPY_STATE_PENDING => Yii::t('models', 'Pending'),
		        self::UPY_STATE_FAILED => Yii::t('models', 'Failed'),
		        self::UPY_STATE_EXPIRED => Yii::t('models', 'Expired'),
		        self::UPY_STATE_HASREFUNDS => Yii::t('models', 'Hasrefunds'),
		        self::UPY_STATE_HASCHARGEBACKS => Yii::t('models', 'Haschargebacks'),
        ];
    }

}
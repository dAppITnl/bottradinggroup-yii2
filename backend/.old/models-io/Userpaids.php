<?php

namespace backend\models;

use Yii;
use \backend\models\base\Userpaids as BaseUserpaids;

/**
 * This is the model class for table "userpaids".
 */
class Userpaids extends BaseUserpaids
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['usrpay_name', 'usrpay_userId', 'usrpay_startdt', 'usrpay_payamount', 'usrpay_paysymbol', 'usrpay_rate', 'usrpay_ratesymbol', 'created_at', 'created_by', 'updated_at', 'updated_by', 'usrpay_createdt', 'usrpay_updatedt'], 'required'],
            [['usrpay_userId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrpay_startdt', 'usrpay_enddt', 'usrpay_paiddt', 'usrpay_createdt', 'usrpay_updatedt', 'usrpay_deletedt'], 'safe'],
            [['usrpay_remarks'], 'string'],
            [['usrpay_name', 'usrpay_payamount', 'usrpay_paysymbol', 'usrpay_rate', 'usrpay_ratesymbol'], 'string', 'max' => 32],
            [['lock'], 'default', 'value' => '0'],
            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ]);
    }
	
    /**
     * @inheritdoc
     */
    public function attributeHints()
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
}

<?php

namespace frontend\models;

use Yii;
use \backend\models\Userpay as BackendUserpay;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "userpay".
 */
class Userpay extends BackendUserpay
{
		public $_acceptMembershipterms;

  public function behaviors()
  {
    return ArrayHelper::merge(
      parent::behaviors(),
        [
          # custom behaviors
        ]
    );
  }

		/**
     * {@inheritdoc}
     */
    public function rules()
    {
      return ArrayHelper::merge( parent::rules(),
				[
					['_acceptMembershipterms', 'required', 'requiredValue'=>1, 'message'=>Yii::t('app', 'You have to accept the membership terms')],
				]
			);
		}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
        /*    'id' => Yii::t('models', 'ID'),
            'upy_lock' => Yii::t('models', 'Lock'),
            'upyusr_id' => Yii::t('models', 'User'),
            'upybsg_id' => Yii::t('models', 'Botsignal'),
            'upycat_state_id' => Yii::t('models', 'Status'),
            'upy_name' => Yii::t('models', 'Name'),
            'upy_startdate' => Yii::t('models', 'Startdate'),
            'upy_enddate' => Yii::t('models', 'Enddate'), */
						'upy_discountcode' => Yii::t('models', 'Discountcode'),
         /*   'upy_payamount' => Yii::t('models', 'Payamount'),
            'upysym_pay_id' => Yii::t('models', 'Paysymbol'),
            'upy_rate' => Yii::t('models', 'Rate'),
            'upysym_rate_id' => Yii::t('models', 'Ratesymbol'), */
						'upy_payprovider' => Yii::t('models', 'Pay via'),
         /*   'upy_paiddt' => Yii::t('models', 'PaidTime'),
            'upy_remarks' => Yii::t('models', 'Remarks'),
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
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'upy_lock' => Yii::t('models', 'Lock'),
            'upyusr_id' => Yii::t('models', 'User'),
            'upybsg_id' => Yii::t('models', 'Botsignal'),
            'upycat_state_id' => Yii::t('models', 'Status'),
            'upy_name' => Yii::t('models', 'Name'),
            'upy_startdate' => Yii::t('models', 'Startdate'),
            'upy_enddate' => Yii::t('models', 'Enddate'),
            'upy_payamount' => Yii::t('models', 'Payamount'),
            'upysym_pay_id' => Yii::t('models', 'Paysymbol'),
            'upy_rate' => Yii::t('models', 'Rate'),
            'upysym_rate_id' => Yii::t('models', 'Ratesymbol'),
            'upy_paiddt' => Yii::t('models', 'PaidTime'),
            'upy_remarks' => Yii::t('models', 'Remarks'),
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

		public function getAcceptMembershipterms()
		{
			return $this->_acceptMembershipterms;
		}

		public function setAcceptMembershipterms($AcceptMembershipterms)
		{
			return $this->_acceptMembershipterms = $AcceptMembershipterms;
		}
}

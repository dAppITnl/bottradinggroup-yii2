<?php

namespace frontend\models;

use Yii;
use \backend\models\Userbot as BackendUserbot;
use frontend\models\Usermember;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "userbot".
 */
class Userbot extends BackendUserbot
{
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
          //# custom validation rules
        ]
    );
  }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
						'ubtumb_id' => Yii::t('models', 'Usermember'),
            'ubtcat_id' => Yii::t('models', 'Category'),
            'ubt_active' => Yii::t('models', 'Active'),
						'ubt_userstartstop' => Yii::t('models', 'Auto start/stop (experimental)'),
            'ubt_name' => Yii::t('models', 'Name'),
            'ubt_3cbotid' => Yii::t('models', '3CBotId'),
            'ubt_3cdealstartjson' => Yii::t('models', '3C Deal Start Message'),
            'ubt_remarks' => Yii::t('models', 'Remarks'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        //    'id' => Yii::t('models', 'ID'),
        //    'ubtumb_id' => Yii::t('models', 'Usermember'),
        //    'ubtcat_id' => Yii::t('models', 'Category'),
        //    'ubt_active' => Yii::t('models', 'Active'),
						'ubt_userstartstop' => Yii::t('models', 'Disable the signal when btc is in extreme downtrend<br>Enable the signal when BTC is neutral or uptrend'),
            'ubt_name' => Yii::t('models', 'Choose a name for this bot, ie the same one as your 3Commas bot'),
        //    'ubt_3cbotid' => Yii::t('models', '3CBotId'),
            'ubt_3cdealstartjson' => Yii::t('models', "Copy the 3Commas bot 'Message for deal start signal'-field text into this field"),
        //    'ubt_remarks' => Yii::t('models', 'Remarks'),
        /*    'ubt_lock' => Yii::t('models', 'Lock'),
            'ubt_createdat' => Yii::t('models', 'CreatedAt'),
            'ubt_createdt' => Yii::t('models', 'Created'),
            'ubtusr_created_id' => Yii::t('models', 'CreatedBy'),
            'ubt_updatedat' => Yii::t('models', 'UpdatedAt'),
            'ubt_updatedt' => Yii::t('models', 'Updated'),
            'ubtusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'ubt_deletedat' => Yii::t('models', 'DeletedAt'),
            'ubt_deletedt' => Yii::t('models', 'Deleted'),
            'ubtusr_deleted_id' => Yii::t('models', 'DeletedBy'),  */
        ]);
    }

  public function getUserbotsOfUser($userId=0, $activeCheckIsTrue=true)
  {
		Yii::trace('** getUserbotsOfUser userid='.$userId);

    if (!empty($userId)) {
			$usermembersQuery = Usermember::find()->select(['id'])->where(['umbusr_id'=>$userId, 'umb_deletedat'=>0]);
			$where = ['ubt_deletedat'=>0];
      $whereUmb = ['IN','ubtumb_id',$usermembersQuery];
      if ($activeCheckIsTrue) $where = array_merge($where, ['ubt_active'=>1]);
			Yii::trace('** getUserbotsOfUser userId='.$userId.' check='.($activeCheckIsTrue?'True':'False').' where='.print_r($where,true));
      $model = Userbot::find()->where($where)->andWhere($whereUmb)->all();
			Yii::trace('** getUserbotsOfUser count='.count($model));
      return $model;
    } else {
      return null;
    }
  }

}

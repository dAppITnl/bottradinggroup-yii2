<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "userbot".
 *
 * @property integer $id
 * @property integer $ubtumb_id
 * @property integer $ubtcat_id
 * @property integer $ubt_active
 * @property integer $ubt_signalstartstop
 * @property integer $ubt_userstartstop
 * @property string $ubt_name
 * @property string $ubt_3cbotid
 * @property string $ubt_3cdealstartjson
 * @property string $ubt_remarks
 * @property integer $ubt_lock
 * @property string $ubt_createdt
 * @property integer $ubtusr_created_id
 * @property string $ubt_updatedt
 * @property integer $ubt_deletedat
 * @property string $ubt_deletedt
 * @property integer $ubtusr_deleted_id
 * @property integer $ubt_createdat
 * @property integer $ubt_updatedat
 * @property integer $ubtusr_updated_id
 *
 * @property \backend\models\Botsignal[] $botsignals
 * @property \backend\models\Signal[] $bsgsigs
 * @property \backend\models\Category $ubtcat
 * @property \backend\models\Usermember $ubtumb
 * @property \backend\models\User $ubtusrCreated
 * @property \backend\models\User $ubtusrDeleted
 * @property \backend\models\User $ubtusrUpdated
 * @property string $aliasModel
 */
abstract class Userbot extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userbot';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => false,
                'updatedByAttribute' => 'ubtusr_updated_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'ubt_createdat',
                'updatedAtAttribute' => 'ubt_updatedat',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ubtumb_id', 'ubtcat_id', 'ubt_name', 'ubt_3cdealstartjson', 'ubt_createdt', 'ubtusr_created_id', 'ubt_updatedt'], 'required'],
            [['ubtumb_id', 'ubtcat_id', 'ubt_active', 'ubt_signalstartstop', 'ubt_userstartstop', 'ubt_lock', 'ubtusr_created_id', 'ubt_deletedat', 'ubtusr_deleted_id'], 'integer'],
            [['ubt_3cdealstartjson', 'ubt_remarks'], 'string'],
            [['ubt_createdt', 'ubt_updatedt', 'ubt_deletedt'], 'safe'],
            [['ubt_name', 'ubt_3cbotid'], 'string', 'max' => 64],
						[['ubt_3cbotid', 'ubt_deletedat'], 'unique', 'targetAttribute' => ['ubt_3cbotid', 'ubt_deletedat'], 'message' => Yii::t('models', 'This bot already exists in 1 of your subscriptions')],
            [['ubtcat_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Category::className(), 'targetAttribute' => ['ubtcat_id' => 'id']],
            [['ubtumb_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Usermember::className(), 'targetAttribute' => ['ubtumb_id' => 'id']],
            [['ubtusr_created_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['ubtusr_created_id' => 'id']],
            [['ubtusr_deleted_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['ubtusr_deleted_id' => 'id']],
            [['ubtusr_updated_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['ubtusr_updated_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'ubtumb_id' => Yii::t('models', 'Usermember'),
            'ubtcat_id' => Yii::t('models', 'Category'),
            'ubt_active' => Yii::t('models', 'Active'),
						'ubt_signalstartstop' => Yii::t('models', 'SignalStartStop'),
						'ubt_userstartstop' => Yii::t('models', 'UserStartStop'),
            'ubt_name' => Yii::t('models', 'Name'),
            'ubt_3cbotid' => Yii::t('models', '3CBotId'),
            'ubt_3cdealstartjson' => Yii::t('models', '3CDealStartJson'),
            'ubt_remarks' => Yii::t('models', 'Remarks'),
            'ubt_lock' => Yii::t('models', 'Lock'),
            'ubt_createdat' => Yii::t('models', 'CreatedAt'),
            'ubt_createdt' => Yii::t('models', 'Created'),
            'ubtusr_created_id' => Yii::t('models', 'CreatedBy'),
            'ubt_updatedat' => Yii::t('models', 'UpdatedAt'),
            'ubt_updatedt' => Yii::t('models', 'Updated'),
            'ubtusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'ubt_deletedat' => Yii::t('models', 'DeletedAt'),
            'ubt_deletedt' => Yii::t('models', 'Deleted'),
            'ubtusr_deleted_id' => Yii::t('models', 'DeletedBy'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'ubtumb_id' => Yii::t('models', 'Usermember'),
            'ubtcat_id' => Yii::t('models', 'Category'),
            'ubt_active' => Yii::t('models', 'Active'),
						'ubt_signalstartstop' => Yii::t('models', 'SignalStartStop'),
						'ubt_userstartstop' => Yii::t('models', 'UserStartStop'),
            'ubt_name' => Yii::t('models', 'Name'),
            'ubt_3cbotid' => Yii::t('models', '3CBotId'),
            'ubt_3cdealstartjson' => Yii::t('models', '3CDealStartJson'),
            'ubt_remarks' => Yii::t('models', 'Remarks'),
            'ubt_lock' => Yii::t('models', 'Lock'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBotsignals()
    {
        return $this->hasMany(\backend\models\Botsignal::className(), ['bsgubt_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
		public function getBsgsigs()
		{
			return $this->hasMany(\backend\models\Signal::className(), ['id' => 'bsgsig_id'])->viaTable('botsignal', ['bsgubt_id' => 'id']);
		}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUbtcat()
    {
        return $this->hasOne(\backend\models\Category::className(), ['id' => 'ubtcat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUbtumb()
    {
        return $this->hasOne(\backend\models\Usermember::className(), ['id' => 'ubtumb_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUbtusrCreated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'ubtusr_created_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUbtusrDeleted()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'ubtusr_deleted_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUbtusrUpdated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'ubtusr_updated_id']);
    }


    
    /**
     * @inheritdoc
     * @return \backend\models\UserbotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\UserbotQuery(get_called_class());
    }


}

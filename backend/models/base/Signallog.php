<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "signallog".
 *
 * @property integer $id
 * @property integer $slgbsg_id
 * @property integer $slgsig_id
 * @property string $slg_status
 * @property string $slg_alertmsg
 * @property string $slg_senddata
 * @property string $slg_message
 * @property string $slg_discordlogchanid
 * @property string $slg_discordlogmessage
 * @property string $slg_discordtologat
 * @property string $slg_discordlogdone
 * @property string $slg_discordlogdelaydone
 * @property string $slg_remarks
 * @property integer $slg_lock
 * @property string $slg_createdt
 * @property string $slg_updatedt
 * @property integer $slg_deletedat
 * @property string $slg_deletedt
 * @property integer $slgusr_deleted_id
 * @property integer $slg_createdat
 * @property integer $slgusr_created_id
 * @property integer $slg_updatedat
 * @property integer $slgusr_updated_id
 *
 * @property \backend\models\Botsignal $slgbsg
 * @property \backend\models\Signal $slgsig
 * @property \backend\models\User $slgusrCreated
 * @property \backend\models\User $slgusrDeleted
 * @property \backend\models\User $slgusrUpdated
 * @property string $aliasModel
 */
abstract class Signallog extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
		* 1jan22 -> json: {'ok':<count>,'error':<count>}
    */
    /*const SLG_STATUS_OK = 'ok';
    const SLG_STATUS_ERROR = 'error';
    const SLG_STATUS_429 = '429';
    const SLG_STATUS_418 = '418';
		*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'signallog';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'slgusr_created_id',
                'updatedByAttribute' => 'slgusr_updated_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'slg_createdat',
                'updatedAtAttribute' => 'slg_updatedat',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slgbsg_id', 'slgsig_id', 'slg_lock', 'slg_deletedat', 'slgusr_deleted_id'], 'integer'],
						[['slg_createdt', 'slg_updatedt'], 'required'],
            [['slg_senddata', 'slg_message', 'slg_remarks'], 'string'],
            [['slg_discordtologat', 'slg_createdt', 'slg_updatedt', 'slg_deletedt'], 'safe'],
						[['slg_status'], 'string', 'max' => 64],
            [['slg_alertmsg', 'slg_discordlogmessage', 'slg_discordlogdone', 'slg_discordlogdelaydone'], 'string', 'max' => 255],
						[['slg_discordlogchanid'], 'string', 'max' => 32],
            [['slgbsg_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Botsignal::className(), 'targetAttribute' => ['slgbsg_id' => 'id']],
						[['slgsig_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\Signal::className(), 'targetAttribute' => ['slgsig_id' => 'id']],
            [['slgusr_created_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['slgusr_created_id' => 'id']],
            [['slgusr_deleted_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['slgusr_deleted_id' => 'id']],
            [['slgusr_updated_id'], 'exist', 'skipOnError' => true, 'targetClass' => \backend\models\User::className(), 'targetAttribute' => ['slgusr_updated_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'slgbsg_id' => Yii::t('models', 'Botsignal'),
						'slgsig_id' => Yii::t('models', 'Signal'),
            'slg_status' => Yii::t('models', 'Status'),
						'slg_alertmsg' => Yii::t('models', 'AlertMsg'),
            'slg_senddata' => Yii::t('models', 'Senddata'),
            'slg_message' => Yii::t('models', 'Message'),
						'slg_discordlogchanid' => Yii::t('models', 'DiscordLogChannelID'),
						'slg_discordlogmessage' => Yii::t('models', 'DiscordLogMessage'),
						'slg_discordtologat' => Yii::t('models', 'DiscordLogAt'),
						'slg_discordlogdone' => Yii::t('models', 'DiscordLogDone'),
						'slg_discordlogdelaydone' => Yii::t('models', 'DiscordLogDelayDone'),
            'slg_remarks' => Yii::t('models', 'Remarks'),
            'slg_lock' => Yii::t('models', 'Lock'),
            'slg_createdat' => Yii::t('models', 'CreatedAt'),
            'slg_createdt' => Yii::t('models', 'Created'),
            'slgusr_created_id' => Yii::t('models', 'CreatedBy'),
            'slg_updatedat' => Yii::t('models', 'UpdatedAt'),
            'slg_updatedt' => Yii::t('models', 'Updated'),
            'slgusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'slg_deletedat' => Yii::t('models', 'DeletedAt'),
            'slg_deletedt' => Yii::t('models', 'Deleted'),
            'slgusr_deleted_id' => Yii::t('models', 'DeletedBy'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
        /*    'id' => Yii::t('models', 'ID'),
            'slgbsg_id' => Yii::t('models', 'Botsignal'),
						'slgsig_id' => Yii::t('models', 'Signal'),
            'slg_status' => Yii::t('models', 'Status'),
						'slg_alertmsg' => Yii::t('models', 'AlertMsg'),
            'slg_senddata' => Yii::t('models', 'Senddata'),
            'slg_message' => Yii::t('models', 'Message'),
						'slg_discordlogchanid' => Yii::t('models', 'DiscordLogChannelID'),
						'slg_discordlogmessage' => Yii::t('models', 'DiscordLogMessage'),
						'slg_discordtologat' => Yii::t('models', 'DiscordLogAt'),
						'slg_discordlogdone' => Yii::t('models', 'DiscordLogDone'),
						'slg_discordlogdelaydone' => Yii::t('models', 'DiscordLogDelayDone'),
            'slg_remarks' => Yii::t('models', 'Remarks'),
            'slg_lock' => Yii::t('models', 'Lock'),
            'slg_createdat' => Yii::t('models', 'CreatedAt'),
            'slg_createdt' => Yii::t('models', 'Created'),
            'slgusr_created_id' => Yii::t('models', 'CreatedBy'),
            'slg_updatedat' => Yii::t('models', 'UpdatedAt'),
            'slg_updatedt' => Yii::t('models', 'Updated'),
            'slgusr_updated_id' => Yii::t('models', 'UpdatedBy'),
            'slg_deletedat' => Yii::t('models', 'DeletedAt'),
            'slg_deletedt' => Yii::t('models', 'Deleted'),
            'slgusr_deleted_id' => Yii::t('models', 'DeletedBy'),  */
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlgbsg()
    {
        return $this->hasOne(\backend\models\Botsignal::className(), ['id' => 'slgbsg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlgsig()
		{
		    return $this->hasOne(\backend\models\Signal::className(), ['id' => 'slgsig_id']);
		}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlgusrCreated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'slgusr_created_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlgusrDeleted()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'slgusr_deleted_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlgusrUpdated()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'slgusr_updated_id']);
    }



    /**
     * @inheritdoc
     * @return \backend\models\SignallogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\SignallogQuery(get_called_class());
    }

}
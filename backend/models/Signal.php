<?php

namespace backend\models;

use Yii;
use \backend\models\base\Signal as BaseSignal;
use yii\helpers\ArrayHelper;
use \backend\models\User;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "signal".
 */
class Signal extends BaseSignal
{

    /**
    * ENUM field values
    */
    const SIG_3CACTIONTEXT_STARTDEAL = 'startdeal';
		const SIG_3CACTIONTEXT_CLOEMP = 'closemp';
		const SIG_3CACTIONTEXT_CLOSEMPALL = 'closempall';
		const SIG_3CACTIONTEXT_CLOSEMPALLSTOPBOT = 'closempallstopbot';
		const SIG_3CACTIONTEXT_CANCEL = 'cancel';
		const SIG_3CACTIONTEXT_CANCELALL = 'cancelall';
		const SIG_3CACTIONTEXT_CANCELALLSTOPBOT = 'cancelallstopbot';
		const SIG_3CACTIONTEXT_STOPBOT = 'stopbot';
		const SIG_3CACTIONTEXT_STARTBOT = 'startbot';
		const SIG_3CACTIONTEXT_STARTBOTSTART = 'startbotstartdeal';
		const SIG_3CACTIONTEXT_STARTTRAILING = 'starttrailing';


		public static function getSig3cActionTextsToSend()
		{
			return [
        //self::SIG_3CACTIONTEXT_STARTDEAL => '', // not available to skip send action part
        self::SIG_3CACTIONTEXT_CLOEMP => 'close_at_market_price',
        self::SIG_3CACTIONTEXT_CLOSEMPALL => 'close_at_market_price_all',
        self::SIG_3CACTIONTEXT_CLOSEMPALLSTOPBOT => 'close_at_market_price_all_and_stop_bot',
        self::SIG_3CACTIONTEXT_CANCEL => 'cancel',
        self::SIG_3CACTIONTEXT_CANCELALL => 'cancel_all',
        self::SIG_3CACTIONTEXT_CANCELALLSTOPBOT => 'cancel_all_and_stop_bot',
        self::SIG_3CACTIONTEXT_STOPBOT => 'stop_bot',
        self::SIG_3CACTIONTEXT_STARTBOT => 'start_bot',
        self::SIG_3CACTIONTEXT_STARTBOTSTART => 'start_bot_and_start_deal',
        self::SIG_3CACTIONTEXT_STARTTRAILING => 'start_trailing',
			];
		}

    /**
     * column sig_3cactiontext ENUM value labels
     * @return array
     */
    public static function optsSig3cActionTexts()
    {
			return [
        self::SIG_3CACTIONTEXT_STARTDEAL => Yii::t('models', 'Start Deal'),
        self::SIG_3CACTIONTEXT_CLOEMP => Yii::t('models', 'Close order at MarketPrice'),
        self::SIG_3CACTIONTEXT_CLOSEMPALL => Yii::t('models', 'Close all deals at MarketPrice'),
        self::SIG_3CACTIONTEXT_CLOSEMPALLSTOPBOT => Yii::t('models', 'Close all deals at MarketPrice and stop bot'),
        self::SIG_3CACTIONTEXT_CANCEL => Yii::t('models', 'Cancel the deal'),
        self::SIG_3CACTIONTEXT_CANCELALL => Yii::t('models', 'Cancel all active deals'),
        self::SIG_3CACTIONTEXT_CANCELALLSTOPBOT => Yii::t('models', 'Cancel all bot deals and stop bot'),
        self::SIG_3CACTIONTEXT_STOPBOT => Yii::t('models', 'Stop bot'),
        self::SIG_3CACTIONTEXT_STARTBOT => Yii::t('models', 'Start bot'),
        self::SIG_3CACTIONTEXT_STARTBOTSTART => Yii::t('models', 'Start bot and deal'),
        self::SIG_3CACTIONTEXT_STARTTRAILING => Yii::t('models', 'Start trailing'),
			];
		}

	public function getSignalsForUserBotsignal($usrid=0) {
		$result = [];
		$usrid = (!empty($usrid) ? $usrid : Yii::$app->user->id);
		$minimalAdminlevel = (GeneralHelper::allowWhenMinimal(User::USR_SITELEVEL_ADMIN, $usrid) == 'true');
 		$sql = "SELECT id, sig_name FROM `signal` WHERE (sig_active=1".($minimalAdminlevel ? " OR sig_active4admin=1" : "").") AND sig_deletedat=0 ORDER BY sig_name ASC";
		$rows = GeneralHelper::runSql($sql);
		foreach($rows as $nr => $row) if (is_numeric($nr)) $result[] = $row;
		return $result;
		//$result = self::find()->select(['id', 'sig_name'])->where(['sig_active'=>1, 'sig_deletedat'=>0])->orderby(['sig_name'=>SORT_ASC])->all();
		//Yii::trace('** getSiganlsForUserBotsignal result count='.count($result));
		//return  \yii\helpers\ArrayHelper::map($result, 'id', 'sig_name');
	}

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
          # custom validation rules
        ]
    );
  }
}

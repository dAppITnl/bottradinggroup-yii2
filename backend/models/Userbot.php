<?php

namespace backend\models;

use Yii;
use \backend\models\base\Userbot as BaseUserbot;
use yii\helpers\ArrayHelper;
use backend\models\Usermember;
use backend\models\User;
use backend\models\Botsignal;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "userbot".
 */
class Userbot extends BaseUserbot
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
          # custom validation rules
        ]
    );
  }

  public function getUserbotsOfUsermembers($umbids='')
  {
    $result = [];
    try {
      if (!empty($umbids)) {
        $sql = "SELECT ubt.id, ubt.ubtumb_id, ubt.ubt_name, ubt.ubt_3cbotid, ubt.ubt_active, ubt.ubt_userstartstop, ubt.ubt_signalstartstop,"
              ." (SELECT CONCAT('[',IFNULL(GROUP_CONCAT(DISTINCT '{\"bsgid\":',bsg2.id,',\"sigid\":',sig2.id,',\"name\":\"',sig2.sig_name,'\",\"active\":',bsg2.bsg_active,'}' SEPARATOR ','),''),']')"
							."  FROM (`signal` sig2, botsignal bsg2, usermember umb2, user usr2)"
              ."  WHERE bsg2.bsgubt_id=ubt.id AND bsg2.bsg_deletedat=0 AND sig2.id=bsg2.bsgsig_id AND sig2.sig_deletedat=0" //  AND bsg.bsg_active=1
								."  AND (sig2.sig_active=1 OR (sig2.sig_active4admin=1 AND umb2.id IN (".$umbids.") AND usr2.id=umb2.umbusr_id AND"
								." usr2.usr_sitelevel IN ('".User::USR_SITELEVEL_ADMIN."','".User::USR_SITELEVEL_SUPERADMIN."','".User::USR_SITELEVEL_DEV."')))"
              ."  ORDER BY sig2.sig_name) as signals"
              ." FROM userbot ubt"
              ." WHERE ubt.ubtumb_id IN (".$umbids.") AND ubt.ubt_deletedat=0" // AND ubt.ubt_active=1
              ." ORDER BY ubt.ubtumb_id, ubt.ubt_3cbotid";
        $rows = GeneralHelper::runSql($sql, false);
        if (!empty($rows)) {
          foreach($rows as $nr => $row) if (is_numeric($nr)) {
            $result[ $row['ubtumb_id'] ][] = $row;
          }
        }
      }
    } catch(\Exception $e) {
      $msg = 'Error: '.$e->getMessage();
      Yii::trace( $msg );
      $result['error'] = $msg;
    }
    Yii::trace('** getUserbotsForUsermembers umbids='.$umbids.' result: '.print_r($result,true));
    return $result;
  }

// ----

  private function _check3cDealstartMessage($umbid='', $dealstart=[])
  {
    $msg = '';
    try {
      Yii::trace('** _check3cDealstartMessage umbid='.$umbid.' dealstart: '.print_r($dealstart,true));
      if (!empty($umbid) && !empty($dealstart) &&
          !empty($dealstart['message_type']) && (strtolower($dealstart['message_type'])=='bot') && !empty($dealstart['bot_id']) && !empty($dealstart['email_token'])) {
        // check if email_token is not known in another membership -> usermember -> userbot
        $sql = "SELECT IFNULL(GROUP_CONCAT(DISTINCT CONCAT(umb.id,'.',ubt.id) SEPARATOR '-'),'') as ids FROM (userbot ubt, usermember umb)"
              ." WHERE ubt.ubtumb_id!=".$umbid." AND (ubt.ubt_3cdealstartjson LIKE '%\"".$dealstart['email_token']."\"%') AND ubt.ubt_deletedat=0"
              ." AND umb.id=ubt.ubtumb_id AND umb.umbmbr_id=(SELECT umb2.umbmbr_id FROM usermember umb2 WHERE umb2.id=".$umbid.")";
        $rows = GeneralHelper::runSql($sql, false);
        Yii::trace('** _check3cDealstartMessage umbid='.$umbid.' check 1 rows: '.print_r($rows,true));
        if (!empty($rows) && !empty($rows[0]['ids'])) {
          $msg = Yii::t('app', 'This bot is already used in another membership. Contact optionally support with this message and reference "{reference}".', ['reference' => $umbid.':'.$rows[0]['ids'] ]);
        } else {
          // check if another email_token (as another 3Commas subscription) exists in other bots of this membership
          $sql = "SELECT COUNT(*) as othercount FROM userbot WHERE ubtumb_id=".$umbid." AND (ubt_3cdealstartjson NOT LIKE '%\"".$dealstart['email_token']."\"%') AND ubt_deletedat=0";
          $rows = GeneralHelper::runSql($sql, false);
          Yii::trace('** _check3cDealstartMessage umbid='.$umbid.' check 2 rows: '.print_r($rows,true));
          if (!empty($rows) && !empty($rows[0]['othercount']) && ($rows[0]['othercount'] > 0)) {
            $msg = Yii::t('app', 'You can not have another 3Commas subscription in this membership. Please buy a new membership.');
          }
        }
      } else {
        $msg = Yii::t('app', "Invalid '3C Deal Start Message': copy the whole 3Commas 'Message for deal start signal'.");
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
    }
    Yii::trace('** _check3cDealstartMessage => msg=' . $msg);
    return $msg;
  }

	public function _addbot($data=[])
	{
		$result = [];

    $usermemberData = [];
    $userbotModel = new Userbot;
    $botsignalModel = null;
    $signalCounts = [];

		if (!empty($data)) {
			try {
				Yii::trace('** _addbot data: '.print_r($data, true));
				Yii::trace('** _addbot POST: '.print_r($_POST, true));
				$umbid = $data['umbid'];
				if (!empty($_POST) && (!empty($_POST['userbot']['ubtumb_id']))) $umbid = $_POST['userbot']['ubtumb_id'];
				if (!empty($umbid)) $usermemberData = Usermember::getUsermembersOfUser(0, false, true, true, $umbid);
				$ubtid = 0;

				if (!empty($_POST)) {
					$ok = false;
					if ($userbotModel->load($_POST)) {
						$dealstart = json_decode($userbotModel->ubt_3cdealstartjson, true);
						$errormsg = self::_check3cDealstartMessage($umbid, $dealstart);
						if (!empty($errormsg)) {
							$userbotModel->addError('_exception', $errormsg);
						} else {
							$userbotModel->ubt_3cbotid = ''.$dealstart['bot_id'];
							$userbotModel->ubtumb_id = $umbid;
							$userbotModel->ubtcat_id = 1;     // asumed 3Commas bot category
							//$userbotModel->ubt_active = 1;    // initially active
							$userbotModel->ubt_createdat = $userbotModel->ubt_updatedat = time();
							$userbotModel->ubtusr_created_id = $userbotModel->ubtusr_updated_id = \Yii::$app->user->id;
							$userbotModel->ubt_createdt = $userbotModel->ubt_updatedt = date('Y-m-d H:i:s', time());
							if ($userbotModel->save()) { $ok = true; $ubtid = $userbotModel->id; }
						}
					}
					if ($ok) {
						$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $umbid );
						$userpayData = Userpay::getCurrentUserpayOfUsermember( $umbid );
						$maxSignals = 1 * $userpayData['upy_maxsignals'];
						$usedSignals = 1 * $signalCounts[$umbid];

						if (($maxSignals == 0) || ($usedSignals < $maxSignals)) {
							return ['redirect' => [$data['redirect_addbotsignal'], 'id'=>$ubtid]]; //  ['/bot/addbotsignal', 'id'=>$ubtid] ); // and add a signal..
						} else {
							return ['redirect' => [$data['redirect_overview']]];   //['/membership/index'] ); // overview
						}
					} elseif (!\Yii::$app->request->isPost) {
						$userbotModel->load($_GET);
					}
				} else {
					$userbotModel->ubtumb_id = $umbid;
					$userbotModel->ubt_active = 1; // initial Active
				}
			} catch (\Exception $e) {
				$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
				$userbotModel->addError('_exception', $msg);
				Yii::trace('** _addbot ERROR ' . $msg);
			}
			$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $umbid );
			// if (!empty($ubtid)) $botsignalModel = BotSignal::find()->where(['bsgubt_id' => $ubtid])->all(); -- al
		}

		Yii::trace('** _addbot id=umbid='.$umbid.' ubtid='.$ubtid.' count botsignal='.print_r($botsignalModel,true).' ');
		return ['render' => ['form'=>$data['render_form'], 'data'=>[ //  'bot_form', [
			'usermemberData' => $usermemberData,
			'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'signalCounts' => $signalCounts,
		]]];
	}

	public function _updatebot($data=[])
	{
		$result = [];

    $usermemberData = null; //new Usermember;
    $userbotModel = null; // new Userbot;
    $botsignalModel = null; //new Botsignal;
    $signalCounts = [];

		if (!empty($data)) {
			try {
				Yii::trace('** _updatebot data:'.print_r($data, true));
				Yii::trace('** _updatebot POST: '.print_r($_POST, true));
				if (!empty($data['id'])
				&& (($userbotModel=Userbot::findOne($data['id'])) !== null)) {
					if (!empty($userbotModel->ubtumb_id)) $usermemberData = Usermember::getUsermembersOfUser(0, false, true, true, $userbotModel->ubtumb_id);
	        $ok = false;
					if (!empty($_POST)) {
						if ($userbotModel->load($_POST)) {
							$dealstart = json_decode($userbotModel->ubt_3cdealstartjson, true);
							$errormsg = self::_check3cDealstartMessage($userbotModel->ubtumb_id, $dealstart);
							if (!empty($errormsg)) {
								$userbotModel->addError('_exception', $errormsg);
							} else {
								$userbotModel->ubt_3cbotid = ''.$dealstart['bot_id'];
								$userbotModel->ubt_updatedat = time();
								$userbotModel->ubtusr_updated_id = \Yii::$app->user->id;
								$userbotModel->ubt_updatedt = date('Y-m-d H:i:s', time());
								if ($userbotModel->save()) { $ok = true; }
							}
						}
						if ($ok) {
							return ['redirect' => [$data['redirect_ok']]];
						} elseif (!\Yii::$app->request->isPost) {
							$userbotModel->load($_GET);
						}
					}
					$signalCounts = Usermember::getUsedSignalCountsOfUsermember( $userbotModel->ubtumb_id );
					$botsignalModel = Botsignal::find()->where(['bsgubt_id'=>$data['id'], 'bsg_deletedat'=>0])->all();
				}
			} catch (\Exception $e) {
				$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
				$userbotModel->addError('_exception', $msg);
				Yii::trace('** _updatebot ERROR ' . $msg);
			}
		}

		return ['render' => ['form'=>$data['render_form'], 'data'=>[
			'usermemberData' => $usermemberData,
			'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'signalCounts' => $signalCounts,
		]]];
	}

}

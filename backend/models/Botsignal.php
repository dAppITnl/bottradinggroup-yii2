<?php

namespace backend\models;

use Yii;
use \backend\models\base\Botsignal as BaseBotsignal;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "botsignal".
 */
class Botsignal extends BaseBotsignal
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

// -----

  public function _addbotsignal($data)
  {
    $userbotModel = null; //new Userbot;
    $botsignalModel = new Botsignal;
		$usermemberData = [];

		if (!empty($data)) {
			try {
				if (!empty($data['ubtid']) && (($userbotModel = Userbot::findOne($data['ubtid'])) !== null)) {
					$botsignalModel->bsg_active = 1;    // initially active
					if (!empty($userbotModel->ubtumb_id)) $usermemberData = Usermember::getUsermembersOfUser(0, false, true, true, $userbotModel->ubtumb_id);

					$ok = false;
					if (!empty($_POST)) {
						if ($botsignalModel->load($_POST)) {
							//$botsignalModel->bsgsig_ids = ((!empty($botsignalModel->bsgsig_ids) && is_array($botsignalModel->bsgsig_ids)) ? implode(',', $botsignalModel->bsgsig_ids) : '');
							$botsignalModel->bsgubt_id = $data['ubtid'];
							//$botsignalModel->bsg_active = 1;    // initially active
							//$botsignalModel->bsg_startdate = $userbotModel->ubtumb->umb_startdate;
							//$botsignalModel->bsg_enddate = $userbotModel->ubtumb->umb_enddate;
							$botsignalModel->bsg_createdat = $botsignalModel->bsg_updatedat = time();
							$botsignalModel->bsgusr_created_id = $botsignalModel->bsgusr_updated_id = \Yii::$app->user->id;
							$botsignalModel->bsg_createdt = $botsignalModel->bsg_updatedt = date('Y-m-d H:i:s', time());
							if ($botsignalModel->save()) { $ok = true; }
						}
						if ($ok) {
							return ['redirect' => $data['redirect_ok']]; // (/*['/', 'id' => $userbotModel->ubtumb_id]*/ Url::previous());
						} elseif (!\Yii::$app->request->isPost) {
							$botsignalModel->load($_GET);
						}
					}
				}
			} catch (\Exception $e) {
				$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
				$botsignalModel->addError('_exception', $msg);
				Yii::trace('** actionAddusersignal ERROR ' . $msg);
			}
		}

		return ['render' => ['form'=>$data['render_form'], 'data'=>[
			'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'usermemberData' => $usermemberData,
		]]];
	}

	public function _updatebotsignal($data)
	{
		$userbotModel = null; //new Userbot;
		$botsignalModel = null; //new Botsignal;
		$usermemberData = [];

		if (!empty($data)) {
			try {
				if (!empty($data['id'])
					&& (($botsignalModel=Botsignal::findOne($data['id'])) !== null)
					&& (($userbotModel=Userbot::findOne($botsignalModel->bsgubt_id)) !== null)) {
					if (!empty($userbotModel->ubtumb_id)) $usermemberData = Usermember::getUsermembersOfUser(0, false, true, true, $userbotModel->ubtumb_id);
					//$botsignalModel->bsgsig_ids = explode(',', $botsignalModel->bsgsig_ids);
					$ok = false;
					if (!empty($_POST)) {
						if ($botsignalModel->load($_POST)) {
							//$botsignalModel->bsgsig_ids = ((!empty($botsignalModel->bsgsig_ids) && is_array($botsignalModel->bsgsig_ids)) ? implode(',', $botsignalModel->bsgsig_ids) : '');
							$botsignalModel->bsg_updatedat = time();
							$botsignalModel->bsgusr_updated_id = \Yii::$app->user->id;
							$botsignalModel->bsg_updatedt = date('Y-m-d H:i:s', time());
							if ($botsignalModel->save()) { $ok = true; }
						}
						if ($ok) {
							return ['redirect' => [$data['redirect_ok'], 'id' => $botsignalModel->bsgubt_id]]; // Url::previous());
						} elseif (!\Yii::$app->request->isPost) {
							$botsignalModel->load($_GET);
						}
					}
				}
			} catch (\Exception $e) {
				$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
				$botsignalModel->addError('_exception', $msg);
				Yii::trace('** _updatebotsignal ERROR ' . $msg);
			}
		}

		return ['render' => ['form'=>$data['render_form'], 'data'=>[
			'userbotModel' => $userbotModel,
			'botsignalModel' => $botsignalModel,
			'usermemberData' => $usermemberData,
		]]];
	}

}

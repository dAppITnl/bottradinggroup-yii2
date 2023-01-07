<?php

namespace backend\models;

use Yii;
use \backend\models\base\Signallog as BaseSignallog;
use yii\helpers\ArrayHelper;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "signallog".
 */
class Signallog extends BaseSignallog
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

	public function getBotdetails($bsgid) {
		$result = [];
		try {
			if (!empty($bsgid)) {
				$sql = "SELECT slg_alertmsg as alertmsg, count(*) as count, min(slg_createdt) as mindate, max(slg_createdt) as maxdate"
							." FROM signallog"
							." WHERE ((slg_senddata LIKE '%\"bsgid\":\"".$bsgid."\"%') OR (slgbsg_id=".$bsgid."))"
							." GROUP BY slg_alertmsg"
							." ORDER BY 4 DESC";
				$rows = GeneralHelper::runSql($sql);
				foreach($rows as $nr => $row) if (is_numeric($nr)) $result[] = $row;
			}
		} catch (\Exception $e) {
			$result['error'] = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
		}
		//Yii::trace('** getBotdetails result: '.print_r($result, true));
		return $result;
	}

}

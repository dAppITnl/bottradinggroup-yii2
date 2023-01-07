<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Userbot;
use common\helpers\GeneralHelper;

/**
* UserbotSearch represents the model behind the search form about `backend\models\Userbot`.
*/
class UserbotSearch extends Userbot
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'ubtumb_id', 'ubtcat_id', 'ubt_active', 'ubt_signalstartstop', 'ubt_userstartstop', 'ubt_lock', 'ubt_createdat', 'ubtusr_created_id', 'ubt_updatedat', 'ubtusr_updated_id', 'ubt_deletedat', 'ubtusr_deleted_id'], 'integer'],
            [['ubt_name', 'ubt_3cbotid', 'ubt_3cdealstartjson', 'ubt_remarks', 'ubt_createdt', 'ubt_updatedt', 'ubt_deletedt'], 'safe'],
];
}

/**
* @inheritdoc
*/
public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

/**
* Creates data provider instance with search query applied
*
* @param array $params
*
* @return ActiveDataProvider
*/
public function search($params)
{
$query = Userbot::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'ubtumb_id' => $this->ubtumb_id,
            'ubtcat_id' => $this->ubtcat_id,
            'ubt_active' => $this->ubt_active,
            'ubt_signalstartstop' => $this->ubt_signalstartstop,
            'ubt_userstartstop' => $this->ubt_userstartstop,
            'ubt_lock' => $this->ubt_lock,
            'ubt_createdat' => $this->ubt_createdat,
            'ubt_createdt' => $this->ubt_createdt,
            'ubtusr_created_id' => $this->ubtusr_created_id,
            'ubt_updatedat' => $this->ubt_updatedat,
            'ubt_updatedt' => $this->ubt_updatedt,
            'ubtusr_updated_id' => $this->ubtusr_updated_id,
            'ubt_deletedat' => ((GeneralHelper::allowWhenMinimal( User::USR_SITELEVEL_SUPERADMIN ) == 'true') ? $this->ubt_deletedat : 0),
            'ubt_deletedt' => $this->ubt_deletedt,
            'ubtusr_deleted_id' => $this->ubtusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'ubt_name', $this->ubt_name])
            ->andFilterWhere(['like', 'ubt_3cbotid', $this->ubt_3cbotid])
            ->andFilterWhere(['like', 'ubt_3cdealstartjson', $this->ubt_3cdealstartjson])
            ->andFilterWhere(['like', 'ubt_remarks', $this->ubt_remarks]);

return $dataProvider;
}
}

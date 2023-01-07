<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Signallog;

/**
* SignallogSearch represents the model behind the search form about `backend\models\Signallog`.
*/
class SignallogSearch extends Signallog
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'slgbsg_id', 'slgsig_id', 'slg_lock', 'slg_createdat', 'slgusr_created_id', 'slg_updatedat', 'slgusr_updated_id', 'slg_deletedat', 'slgusr_deleted_id'], 'integer'],
            [['slg_status', 'slg_alertmsg', 'slg_senddata', 'slg_message', 'slg_discordlogchanid', 'slg_discordlogmessage', 'slg_discordtologat', 'slg_discordlogdone', 'slg_discordlogdelaydone', 'slg_remarks', 'slg_createdt', 'slg_updatedt', 'slg_deletedt'], 'safe'],
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
$query = Signallog::find();

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
            'slgbsg_id' => $this->slgbsg_id,
            'slgsig_id' => $this->slgsig_id,
            'slg_discordtologat' => $this->slg_discordtologat,
            'slg_lock' => $this->slg_lock,
            'slg_createdat' => $this->slg_createdat,
            'slg_createdt' => $this->slg_createdt,
            'slgusr_created_id' => $this->slgusr_created_id,
            'slg_updatedat' => $this->slg_updatedat,
            'slg_updatedt' => $this->slg_updatedt,
            'slgusr_updated_id' => $this->slgusr_updated_id,
            'slg_deletedat' => $this->slg_deletedat,
            'slg_deletedt' => $this->slg_deletedt,
            'slgusr_deleted_id' => $this->slgusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'slg_status', $this->slg_status])
            ->andFilterWhere(['like', 'slg_alertmsg', $this->slg_alertmsg])
            ->andFilterWhere(['like', 'slg_senddata', $this->slg_senddata])
            ->andFilterWhere(['like', 'slg_message', $this->slg_message])
            ->andFilterWhere(['like', 'slg_discordlogchanid', $this->slg_discordlogchanid])
            ->andFilterWhere(['like', 'slg_discordlogmessage', $this->slg_discordlogmessage])
            ->andFilterWhere(['like', 'slg_discordlogdone', $this->slg_discordlogdone])
            ->andFilterWhere(['like', 'slg_discordlogdelaydone', $this->slg_discordlogdelaydone])
            ->andFilterWhere(['like', 'slg_remarks', $this->slg_remarks]);

return $dataProvider;
}
}
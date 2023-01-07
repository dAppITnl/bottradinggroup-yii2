<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Signal;

/**
* SignalSearch represents the model behind the search form about `backend\models\Signal`.
*/
class SignalSearch extends Signal
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'sigsym_base_id', 'sigsym_quote_id', 'sig_runenabled', 'sig_active', 'sig_active4admin', 'sig_maxbots', 'sig_discorddelayminutes', 'sig_lock', 'sig_createdat', 'sigusr_created_id', 'sig_updatedat', 'sigusr_updated_id', 'sig_deletedat', 'sigusr_deleted_id'], 'integer'],
            [['sigcat_ids', 'sigmbr_ids', 'sig_code', 'sig_name', 'sig_3cactiontext', 'sig_3callowedquotes', 'sig_discordlogchanid', 'sig_discordlogdelaychanid', 'sig_discordmessage', 'sig_description', 'sig_remarks', 'sig_createdt', 'sig_updatedt', 'sig_deletedt'], 'safe'],
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
$query = Signal::find();

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
            'sigsym_base_id' => $this->sigsym_base_id,
            'sigsym_quote_id' => $this->sigsym_quote_id,
            'sig_runenabled' => $this->sig_runenabled,
            'sig_active' => $this->sig_active,
            'sig_active4admin' => $this->sig_active4admin,
            'sig_maxbots' => $this->sig_maxbots,
            'sig_discorddelayminutes' => $this->sig_discorddelayminutes,
            'sig_lock' => $this->sig_lock,
            'sig_createdt' => $this->sig_createdt,
            'sig_createdat' => $this->sig_createdat,
            'sigusr_created_id' => $this->sigusr_created_id,
            'sig_updatedat' => $this->sig_updatedat,
            'sig_updatedt' => $this->sig_updatedt,
            'sigusr_updated_id' => $this->sigusr_updated_id,
            'sig_deletedat' => $this->sig_deletedat,
            'sig_deletedt' => $this->sig_deletedt,
            'sigusr_deleted_id' => $this->sigusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'sigcat_ids', $this->sigcat_ids])
            ->andFilterWhere(['like', 'sigmbr_ids', $this->sigmbr_ids])
            ->andFilterWhere(['like', 'sig_code', $this->sig_code])
            ->andFilterWhere(['like', 'sig_name', $this->sig_name])
            ->andFilterWhere(['like', 'sig_3cactiontext', $this->sig_3cactiontext])
            ->andFilterWhere(['like', 'sig_3callowedquotes', $this->sig_3callowedquotes])
            ->andFilterWhere(['like', 'sig_discordlogchanid', $this->sig_discordlogchanid])
            ->andFilterWhere(['like', 'sig_discordlogdelaychanid', $this->sig_discordlogdelaychanid])
            ->andFilterWhere(['like', 'sig_discordmessage', $this->sig_discordmessage])
            ->andFilterWhere(['like', 'sig_description', $this->sig_description])
            ->andFilterWhere(['like', 'sig_remarks', $this->sig_remarks]);

return $dataProvider;
}
}
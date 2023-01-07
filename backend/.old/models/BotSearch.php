<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bot;

/**
* BotSearch represents the model behind the search form about `backend\models\Bot`.
*/
class BotSearch extends Bot
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'botcat_id', 'bot_lock', 'botsym_cost_id', 'bot_createdat', 'botusr_created_id', 'bot_updatedat', 'botusr_updated_id', 'bot_deletedat', 'botusr_deleted_id'], 'integer'],
            [['bot_name', 'bot_3cbotid', 'bot_dealstartjson', 'bot_createdt', 'bot_updatedt', 'bot_deletedt', 'bot_remarks'], 'safe'],
            [['bot_costmonth'], 'number'],
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
$query = Bot::find();

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
            'botcat_id' => $this->botcat_id,
            'bot_lock' => $this->bot_lock,
            'bot_costmonth' => $this->bot_costmonth,
            'botsym_cost_id' => $this->botsym_cost_id,
            'bot_createdat' => $this->bot_createdat,
            'botusr_created_id' => $this->botusr_created_id,
            'bot_updatedat' => $this->bot_updatedat,
            'botusr_updated_id' => $this->botusr_updated_id,
            'bot_deletedat' => $this->bot_deletedat,
            'botusr_deleted_id' => $this->botusr_deleted_id,
            'bot_createdt' => $this->bot_createdt,
            'bot_updatedt' => $this->bot_updatedt,
            'bot_deletedt' => $this->bot_deletedt,
        ]);

        $query->andFilterWhere(['like', 'bot_name', $this->bot_name])
            ->andFilterWhere(['like', 'bot_3cbotid', $this->bot_3cbotid])
            ->andFilterWhere(['like', 'bot_dealstartjson', $this->bot_dealstartjson])
            ->andFilterWhere(['like', 'bot_remarks', $this->bot_remarks]);

return $dataProvider;
}
}
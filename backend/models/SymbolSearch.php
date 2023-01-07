<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Symbol;

/**
* SymbolSearch represents the model behind the search form about `backend\models\Symbol`.
*/
class SymbolSearch extends Symbol
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'sym_isquote', 'sym_ispair', 'symsym_base_id', 'symsym_quote_id', 'symsym_network_id', 'sym_lock', 'sym_createdat', 'symusr_created_id', 'sym_updatedat', 'symusr_updated_id', 'sym_deletedat', 'symusr_deleted_id'], 'integer'],
            [['sym_type', 'sym_contractaddress', 'sym_code', 'sym_symbol', 'sym_name', 'sym_html', 'sym_description', 'sym_rateurl', 'sym_remarks', 'sym_createdt', 'sym_updatedt', 'sym_deletedt'], 'safe'],
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
$query = Symbol::find();

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
            'sym_isquote' => $this->sym_isquote,
            'sym_ispair' => $this->sym_ispair,
            'symsym_base_id' => $this->symsym_base_id,
            'symsym_quote_id' => $this->symsym_quote_id,
            'symsym_network_id' => $this->symsym_network_id,
            'sym_lock' => $this->sym_lock,
            'sym_createdat' => $this->sym_createdat,
            'sym_createdt' => $this->sym_createdt,
            'symusr_created_id' => $this->symusr_created_id,
            'sym_updatedat' => $this->sym_updatedat,
            'sym_updatedt' => $this->sym_updatedt,
            'symusr_updated_id' => $this->symusr_updated_id,
            'sym_deletedat' => $this->sym_deletedat,
            'sym_deletedt' => $this->sym_deletedt,
            'symusr_deleted_id' => $this->symusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'sym_type', $this->sym_type])
            ->andFilterWhere(['like', 'sym_contractaddress', $this->sym_contractaddress])
            ->andFilterWhere(['like', 'sym_code', $this->sym_code])
            ->andFilterWhere(['like', 'sym_symbol', $this->sym_symbol])
            ->andFilterWhere(['like', 'sym_name', $this->sym_name])
            ->andFilterWhere(['like', 'sym_html', $this->sym_html])
            ->andFilterWhere(['like', 'sym_description', $this->sym_description])
            ->andFilterWhere(['like', 'sym_rateurl', $this->sym_rateurl])
            ->andFilterWhere(['like', 'sym_remarks', $this->sym_remarks]);

return $dataProvider;
}
}
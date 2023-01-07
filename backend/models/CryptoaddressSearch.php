<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Cryptoaddress;

/**
* CryptoaddressSearch represents the model behind the search form about `backend\models\Cryptoaddress`.
*/
class CryptoaddressSearch extends Cryptoaddress
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'cadsym_id', 'cadusr_owner_id', 'cad_active', 'cad_ismainnet', 'cad_decimals', 'cad_lock', 'cad_createdat', 'cadusr_created_id', 'cad_updatedat', 'cadusr_updated_id', 'cad_deletedat', 'cadusr_deleted_id'], 'integer'],
            [['cad_type', 'cadmbr_ids', 'cad_payprovider', 'cad_networkname', 'cad_networksettings', 'cad_tokenmetadata', 'cad_code', 'cad_name', 'cad_address', 'cad_memo', 'cad_contract', 'cad_description', 'cad_remarks', 'cad_createdt', 'cad_updatedt', 'cad_deletedt'], 'safe'],
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
$query = Cryptoaddress::find();

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
            'cadsym_id' => $this->cadsym_id,
            'cadusr_owner_id' => $this->cadusr_owner_id,
            'cad_active' => $this->cad_active,
            'cad_ismainnet' => $this->cad_ismainnet,
            'cad_decimals' => $this->cad_decimals,
            'cad_lock' => $this->cad_lock,
            'cad_createdat' => $this->cad_createdat,
            'cad_createdt' => $this->cad_createdt,
            'cadusr_created_id' => $this->cadusr_created_id,
            'cad_updatedat' => $this->cad_updatedat,
            'cad_updatedt' => $this->cad_updatedt,
            'cadusr_updated_id' => $this->cadusr_updated_id,
            'cad_deletedat' => $this->cad_deletedat,
            'cad_deletedt' => $this->cad_deletedt,
            'cadusr_deleted_id' => $this->cadusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'cad_type', $this->cad_type])
            ->andFilterWhere(['like', 'cadmbr_ids', $this->cadmbr_ids])
            ->andFilterWhere(['like', 'cad_payprovider', $this->cad_payprovider])
            ->andFilterWhere(['like', 'cad_networkname', $this->cad_networkname])
            ->andFilterWhere(['like', 'cad_networksettings', $this->cad_networksettings])
            ->andFilterWhere(['like', 'cad_tokenmetadata', $this->cad_tokenmetadata])
            ->andFilterWhere(['like', 'cad_code', $this->cad_code])
            ->andFilterWhere(['like', 'cad_name', $this->cad_name])
            ->andFilterWhere(['like', 'cad_address', $this->cad_address])
            ->andFilterWhere(['like', 'cad_memo', $this->cad_memo])
            ->andFilterWhere(['like', 'cad_contract', $this->cad_contract])
            ->andFilterWhere(['like', 'cad_description', $this->cad_description])
            ->andFilterWhere(['like', 'cad_remarks', $this->cad_remarks]);

return $dataProvider;
}
}
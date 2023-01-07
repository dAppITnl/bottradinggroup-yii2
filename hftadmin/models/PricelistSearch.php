<?php

namespace hftadmin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hftadmin\models\Pricelist;

/**
* PricelistSearch represents the model behind the search form about `hftadmin\models\Pricelist`.
*/
class PricelistSearch extends Pricelist
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'prlmbr_id', 'prlcat_id', 'prl_active', 'prl_active4admin', 'prl_maxsignals', 'prl_allowedtimes', 'prlsym_id', 'prl_lock', 'prl_createdat', 'prlusr_created_id', 'prl_updatedat', 'prlusr_updated_id', 'prl_deletedat', 'prlusr_deleted_id'], 'integer'],
            [['prlcad_crypto_ids', 'prl_name', 'prl_pretext', 'prl_posttext', 'prl_startdate', 'prl_enddate', 'prl_percode', 'prl_discountcode', 'prl_remarks', 'prl_createdt', 'prl_updatedt', 'prl_deletedt'], 'safe'],
            [['prl_price'], 'number'],
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
$query = Pricelist::find();

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
            'prlmbr_id' => $this->prlmbr_id,
            'prlcat_id' => $this->prlcat_id,
            'prl_active' => $this->prl_active,
            'prl_active4admin' => $this->prl_active4admin,
            'prl_startdate' => $this->prl_startdate,
            'prl_enddate' => $this->prl_enddate,
            'prl_maxsignals' => $this->prl_maxsignals,
            'prl_allowedtimes' => $this->prl_allowedtimes,
            'prlsym_id' => $this->prlsym_id,
            'prl_price' => $this->prl_price,
            'prl_lock' => $this->prl_lock,
            'prl_createdat' => $this->prl_createdat,
            'prl_createdt' => $this->prl_createdt,
            'prlusr_created_id' => $this->prlusr_created_id,
            'prl_updatedat' => $this->prl_updatedat,
            'prl_updatedt' => $this->prl_updatedt,
            'prlusr_updated_id' => $this->prlusr_updated_id,
            'prl_deletedat' => $this->prl_deletedat,
            'prl_deletedt' => $this->prl_deletedt,
            'prlusr_deleted_id' => $this->prlusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'prlcad_crypto_ids', $this->prlcad_crypto_ids])
						->andFilterWhere(['in', 'prlcat_id', Pricelist::HFTADMIN_CATEGORIES_PRL]) // Only prices for hftadmin
            ->andFilterWhere(['like', 'prl_name', $this->prl_name])
            ->andFilterWhere(['like', 'prl_pretext', $this->prl_pretext])
            ->andFilterWhere(['like', 'prl_posttext', $this->prl_posttext])
            ->andFilterWhere(['like', 'prl_percode', $this->prl_percode])
            ->andFilterWhere(['like', 'prl_discountcode', $this->prl_discountcode])
            ->andFilterWhere(['like', 'prl_remarks', $this->prl_remarks]);

return $dataProvider;
}
}

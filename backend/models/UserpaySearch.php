<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Userpay;

/**
* UserpaySearch represents the model behind the search form about `backend\models\Userpay`.
*/
class UserpaySearch extends Userpay
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'upyusr_id', 'upyumb_id', 'upymbr_id', 'upycat_id', 'upyprl_id', 'upy_maxsignals', 'upysym_pay_id', 'upysym_crypto_id', 'upysym_rate_id', 'upycad_to_id', 'upy_lock', 'upy_createdat', 'upyusr_created_id', 'upy_updatedat', 'upyusr_updated_id', 'upy_deletedat', 'upyusr_deleted_id'], 'integer'],
            [['upy_state', 'upy_name', 'upy_percode', 'upy_startdate', 'upy_enddate', 'upy_discountcode', 'upy_rate', 'upy_payprovider', 'upy_providermode', 'upy_providername', 'upy_toaccount', 'upy_payref', 'upy_providerid', 'upy_payreply', 'upy_fromaccount', 'upy_paiddt', 'upy_remarks', 'upy_createdt', 'upy_updatedt', 'upy_deletedt'], 'safe'],
            [['upy_payamount', 'upy_cryptoamount'], 'number'],
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
$query = Userpay::find();

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
            'upyusr_id' => $this->upyusr_id,
            'upyumb_id' => $this->upyumb_id,
            'upymbr_id' => $this->upymbr_id,
            'upycat_id' => $this->upycat_id,
            'upyprl_id' => $this->upyprl_id,
            'upy_maxsignals' => $this->upy_maxsignals,
            'upy_startdate' => $this->upy_startdate,
            'upy_enddate' => $this->upy_enddate,
            'upy_payamount' => $this->upy_payamount,
            'upy_cryptoamount' => $this->upy_cryptoamount,
            'upysym_pay_id' => $this->upysym_pay_id,
            'upysym_crypto_id' => $this->upysym_crypto_id,
            'upysym_rate_id' => $this->upysym_rate_id,
            'upycad_to_id' => $this->upycad_to_id,
            'upy_paiddt' => $this->upy_paiddt,
            'upy_lock' => $this->upy_lock,
            'upy_createdat' => $this->upy_createdat,
            'upy_createdt' => $this->upy_createdt,
            'upyusr_created_id' => $this->upyusr_created_id,
            'upy_updatedat' => $this->upy_updatedat,
            'upy_updatedt' => $this->upy_updatedt,
            'upyusr_updated_id' => $this->upyusr_updated_id,
            'upy_deletedat' => $this->upy_deletedat,
            'upy_deletedt' => $this->upy_deletedt,
            'upyusr_deleted_id' => $this->upyusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'upy_state', $this->upy_state])
            ->andFilterWhere(['like', 'upy_name', $this->upy_name])
            ->andFilterWhere(['like', 'upy_percode', $this->upy_percode])
            ->andFilterWhere(['like', 'upy_discountcode', $this->upy_discountcode])
            ->andFilterWhere(['like', 'upy_rate', $this->upy_rate])
            ->andFilterWhere(['like', 'upy_payprovider', $this->upy_payprovider])
            ->andFilterWhere(['like', 'upy_providermode', $this->upy_providermode])
            ->andFilterWhere(['like', 'upy_providername', $this->upy_providername])
            ->andFilterWhere(['like', 'upy_toaccount', $this->upy_toaccount])
            ->andFilterWhere(['like', 'upy_payref', $this->upy_payref])
            ->andFilterWhere(['like', 'upy_providerid', $this->upy_providerid])
            ->andFilterWhere(['like', 'upy_payreply', $this->upy_payreply])
            ->andFilterWhere(['like', 'upy_fromaccount', $this->upy_fromaccount])
            ->andFilterWhere(['like', 'upy_remarks', $this->upy_remarks]);

return $dataProvider;
}
}
<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Userpaids;

/**
 * backend\models\UserpaidsSearch represents the model behind the search form about `backend\models\Userpaids`.
 */
 class UserpaidsSearch extends Userpaids
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'usrpay_userId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrpay_name', 'usrpay_startdt', 'usrpay_enddt', 'usrpay_payamount', 'usrpay_paysymbol', 'usrpay_rate', 'usrpay_ratesymbol', 'usrpay_paiddt', 'usrpay_createdt', 'usrpay_updatedt', 'usrpay_deletedt', 'usrpay_remarks'], 'safe'],
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
        $query = Userpaids::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'usrpay_userId' => $this->usrpay_userId,
            'usrpay_startdt' => $this->usrpay_startdt,
            'usrpay_enddt' => $this->usrpay_enddt,
            'usrpay_paiddt' => $this->usrpay_paiddt,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'usrpay_createdt' => $this->usrpay_createdt,
            'usrpay_updatedt' => $this->usrpay_updatedt,
            'usrpay_deletedt' => $this->usrpay_deletedt,
        ]);

        $query->andFilterWhere(['like', 'usrpay_name', $this->usrpay_name])
            ->andFilterWhere(['like', 'usrpay_payamount', $this->usrpay_payamount])
            ->andFilterWhere(['like', 'usrpay_paysymbol', $this->usrpay_paysymbol])
            ->andFilterWhere(['like', 'usrpay_rate', $this->usrpay_rate])
            ->andFilterWhere(['like', 'usrpay_ratesymbol', $this->usrpay_ratesymbol])
            ->andFilterWhere(['like', 'usrpay_remarks', $this->usrpay_remarks]);

        return $dataProvider;
    }
}

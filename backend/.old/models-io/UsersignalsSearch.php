<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Usersignals;

/**
 * backend\models\UsersignalsSearch represents the model behind the search form about `backend\models\Usersignals`.
 */
 class UsersignalsSearch extends Usersignals
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'usrsig_lock', 'usrsig_userId', 'usrsig_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usrsig_name', 'usrsig_emailtoken', 'usrsig_pair', 'usrsig_createdt', 'usrsig_updatedt', 'usrsig_deletedt', 'usrsig_remarks'], 'safe'],
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
        $query = Usersignals::find();

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
            'usrsig_lock' => $this->usrsig_lock,
            'usrsig_userId' => $this->usrsig_userId,
            'usrsig_botId' => $this->usrsig_botId,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'usrsig_createdt' => $this->usrsig_createdt,
            'usrsig_updatedt' => $this->usrsig_updatedt,
            'usrsig_deletedt' => $this->usrsig_deletedt,
        ]);

        $query->andFilterWhere(['like', 'usrsig_name', $this->usrsig_name])
            ->andFilterWhere(['like', 'usrsig_emailtoken', $this->usrsig_emailtoken])
            ->andFilterWhere(['like', 'usrsig_pair', $this->usrsig_pair])
            ->andFilterWhere(['like', 'usrsig_remarks', $this->usrsig_remarks]);

        return $dataProvider;
    }
}

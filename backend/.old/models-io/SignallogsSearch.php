<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Signallogs;

/**
 * backend\models\SignallogsSearch represents the model behind the search form about `backend\models\Signallogs`.
 */
 class SignallogsSearch extends Signallogs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'siglog_userId', 'siglog_botId', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['siglog_name', 'siglog_type', 'siglog_message', 'siglog_createdt'], 'safe'],
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
        $query = Signallogs::find();

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
            'siglog_userId' => $this->siglog_userId,
            'siglog_botId' => $this->siglog_botId,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'siglog_createdt' => $this->siglog_createdt,
        ]);

        $query->andFilterWhere(['like', 'siglog_name', $this->siglog_name])
            ->andFilterWhere(['like', 'siglog_type', $this->siglog_type])
            ->andFilterWhere(['like', 'siglog_message', $this->siglog_message]);

        return $dataProvider;
    }
}

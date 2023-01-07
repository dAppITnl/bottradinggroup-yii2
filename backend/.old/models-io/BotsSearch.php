<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bots;

/**
 * backend\models\BotsSearch represents the model behind the search form about `backend\models\Bots`.
 */
 class BotsSearch extends Bots
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bot_lock', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['bot_name', 'bot_3cbotid', 'bot_dealstartjson', 'bot_createdt', 'bot_updatedt', 'bot_deletedt', 'bot_remarks'], 'safe'],
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
        $query = Bots::find();

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
            'bot_lock' => $this->bot_lock,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
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

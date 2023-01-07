<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Usersignal;

/**
* UsersignalSearch represents the model behind the search form about `backend\models\Usersignal`.
*/
class UsersignalSearch extends Usersignal
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'usg_lock', 'usgusr_id', 'usgbot_id', 'usg_createdat', 'usgusr_created_id', 'usg_updatedat', 'usgusr_updated_id', 'usg_deletedat', 'usgusr_deleted_id'], 'integer'],
            [['usg_name', 'usg_emailtoken', 'usg_pair', 'usg_createdt', 'usg_updatedt', 'usg_deletedt', 'usg_remarks'], 'safe'],
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
$query = Usersignal::find();

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
            'usg_lock' => $this->usg_lock,
            'usgusr_id' => $this->usgusr_id,
            'usgbot_id' => $this->usgbot_id,
            'usg_createdat' => $this->usg_createdat,
            'usgusr_created_id' => $this->usgusr_created_id,
            'usg_updatedat' => $this->usg_updatedat,
            'usgusr_updated_id' => $this->usgusr_updated_id,
            'usg_deletedat' => $this->usg_deletedat,
            'usgusr_deleted_id' => $this->usgusr_deleted_id,
            'usg_createdt' => $this->usg_createdt,
            'usg_updatedt' => $this->usg_updatedt,
            'usg_deletedt' => $this->usg_deletedt,
        ]);

        $query->andFilterWhere(['like', 'usg_name', $this->usg_name])
            ->andFilterWhere(['like', 'usg_emailtoken', $this->usg_emailtoken])
            ->andFilterWhere(['like', 'usg_pair', $this->usg_pair])
            ->andFilterWhere(['like', 'usg_remarks', $this->usg_remarks]);

return $dataProvider;
}
}
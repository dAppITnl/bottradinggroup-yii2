<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Botsignal;
use backend\models\User;
use common\helpers\GeneralHelper;

/**
* BotsignalSearch represents the model behind the search form about `backend\models\Botsignal`.
*/
class BotsignalSearch extends Botsignal
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'bsgubt_id', 'bsgsig_id', 'bsg_active', 'bsg_lock', 'bsg_createdat', 'bsgusr_created_id', 'bsg_updatedat', 'bsgusr_updated_id', 'bsg_deletedat', 'bsgusr_deleted_id'], 'integer'],
            [['bsg_remarks', 'bsg_createdt', 'bsg_updatedt', 'bsg_deletedt'], 'safe'],
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
$query = Botsignal::find();

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
            'bsgubt_id' => $this->bsgubt_id,
            'bsgsig_id' => $this->bsgsig_id,
            'bsg_active' => $this->bsg_active,
            'bsg_lock' => $this->bsg_lock,
            'bsg_createdat' => $this->bsg_createdat,
            'bsg_createdt' => $this->bsg_createdt,
            'bsgusr_created_id' => $this->bsgusr_created_id,
            'bsg_updatedat' => $this->bsg_updatedat,
            'bsg_updatedt' => $this->bsg_updatedt,
            'bsgusr_updated_id' => $this->bsgusr_updated_id,
            'bsg_deletedat' => ((GeneralHelper::allowWhenMinimal( User::USR_SITELEVEL_SUPERADMIN ) == 'true') ? $this->bsg_deletedat : 0),
            'bsg_deletedt' => $this->bsg_deletedt,
            'bsgusr_deleted_id' => $this->bsgusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'bsg_remarks', $this->bsg_remarks]);

return $dataProvider;
}
}

<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Membership;

/**
* MembershipSearch represents the model behind the search form about `backend\models\Membership`.
*/
class MembershipSearch extends Membership
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'mbrcat_id', 'mbr_active', 'mbr_active4admin', 'mbr_order', 'mbr_groupnr', 'mbr_lock', 'mbr_createdat', 'mbrusr_created_id', 'mbr_updatedat', 'mbrusr_updated_id', 'mbr_deletedat', 'mbrusr_deleted_id'], 'integer'],
            [['mbr_language', 'mbr_code', 'mbr_title', 'mbr_cardbody', 'mbr_detailbody', 'mbr_discordroles', 'mbr_discordlogchanid', 'mbr_remarks', 'mbr_createdt', 'mbr_updatedt', 'mbr_deletedt'], 'safe'],
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
$query = Membership::find();

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
            'mbrcat_id' => $this->mbrcat_id,
            'mbr_active' => $this->mbr_active,
            'mbr_active4admin' => $this->mbr_active4admin,
            'mbr_order' => $this->mbr_order,
            'mbr_groupnr' => $this->mbr_groupnr,
            'mbr_lock' => $this->mbr_lock,
            'mbr_createdat' => $this->mbr_createdat,
            'mbr_createdt' => $this->mbr_createdt,
            'mbrusr_created_id' => $this->mbrusr_created_id,
            'mbr_updatedat' => $this->mbr_updatedat,
            'mbr_updatedt' => $this->mbr_updatedt,
            'mbrusr_updated_id' => $this->mbrusr_updated_id,
            'mbr_deletedat' => $this->mbr_deletedat,
            'mbr_deletedt' => $this->mbr_deletedt,
            'mbrusr_deleted_id' => $this->mbrusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'mbr_language', $this->mbr_language])
            ->andFilterWhere(['like', 'mbr_code', $this->mbr_code])
            ->andFilterWhere(['like', 'mbr_title', $this->mbr_title])
            ->andFilterWhere(['like', 'mbr_cardbody', $this->mbr_cardbody])
            ->andFilterWhere(['like', 'mbr_detailbody', $this->mbr_detailbody])
            ->andFilterWhere(['like', 'mbr_discordroles', $this->mbr_discordroles])
            ->andFilterWhere(['like', 'mbr_discordlogchanid', $this->mbr_discordlogchanid])
            ->andFilterWhere(['like', 'mbr_remarks', $this->mbr_remarks]);

return $dataProvider;
}
}
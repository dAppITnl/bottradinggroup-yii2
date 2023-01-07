<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Usermember;
use common\helpers\GeneralHelper;

/**
* UsermemberSearch represents the model behind the search form about `backend\models\Usermember`.
*/
class UsermemberSearch extends Usermember
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'umbusr_id', 'umbmbr_id', 'umbprl_id', 'umbupy_id', 'umb_active', 'umb_maxsignals', 'umb_lock', 'umb_createdat', 'umbusr_created_id', 'umb_updatedat', 'umbusr_updated_id', 'umb_deletedat', 'umbusr_deleted_id'], 'integer'],
            [['umb_name', 'umb_roles', 'umb_startdate', 'umb_enddate', 'umb_paystartdate', 'umb_payenddate', 'umb_remarks', 'umb_createdt', 'umb_updatedt', 'umb_deletedt'], 'safe'],
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
$query = Usermember::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

Yii::trace('** UsermemberSearch minimal SA='.(GeneralHelper::allowWhenMinimal( User::USR_SITELEVEL_SUPERADMIN ) == 'true') ? 'True':'False');


$query->andFilterWhere([
            'id' => $this->id,
            'umbusr_id' => $this->umbusr_id,
            'umbmbr_id' => $this->umbmbr_id,
            'umbprl_id' => $this->umbprl_id,
            'umbupy_id' => $this->umbupy_id,
            'umb_active' => $this->umb_active,
            'umb_startdate' => $this->umb_startdate,
            'umb_enddate' => $this->umb_enddate,
            'umb_paystartdate' => $this->umb_paystartdate,
            'umb_payenddate' => $this->umb_payenddate,
            'umb_maxsignals' => $this->umb_maxsignals,
            'umb_lock' => $this->umb_lock,
            'umb_createdat' => $this->umb_createdat,
            'umb_createdt' => $this->umb_createdt,
            'umbusr_created_id' => $this->umbusr_created_id,
            'umb_updatedat' => $this->umb_updatedat,
            'umb_updatedt' => $this->umb_updatedt,
            'umbusr_updated_id' => $this->umbusr_updated_id,
            'umb_deletedat' => ((GeneralHelper::allowWhenMinimal( User::USR_SITELEVEL_SUPERADMIN ) == 'true') ? $this->umb_deletedat : 0 ),
            'umb_deletedt' => $this->umb_deletedt,
            'umbusr_deleted_id' => $this->umbusr_deleted_id,
        ]);

        $query->andFilterWhere(['like', 'umb_name', $this->umb_name])
            ->andFilterWhere(['like', 'umb_roles', $this->umb_roles])
            ->andFilterWhere(['like', 'umb_remarks', $this->umb_remarks]);

return $dataProvider;
}
}

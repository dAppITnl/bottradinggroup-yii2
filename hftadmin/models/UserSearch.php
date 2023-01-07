<?php

namespace hftadmin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hftadmin\models\User;
use backend\models\User as BackendUser;
use common\helpers\GeneralHelper;

/**
* UserSearch represents the model behind the search form about `hftadmin\models\User`.
*/
class UserSearch extends User
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'status', 'usr_signalactive', 'usr_discordid', 'usr_lock', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['usr_language', 'username', 'usr_password', 'usr_2fasecret', 'email', 'usr_firstname', 'usr_lastname', 'usr_countrycode', 'usr_sitelevel', 'usr_sitecsstheme', 'usr_moralisid', 'usr_discordusername', 'usr_discordnick', 'usr_discordjoinedat', 'usr_discordroles', 'password_hash', 'password_reset_token', 'auth_key', 'verification_token', 'usr_remarks', 'usr_createdt', 'usr_updatedt', 'usr_deletedt'], 'safe'],
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
$query = User::find();

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
            'status' => $this->status,
            'usr_signalactive' => $this->usr_signalactive,
            'usr_discordjoinedat' => $this->usr_discordjoinedat,
            'usr_discordid' => $this->usr_discordid,
            'usr_lock' => $this->usr_lock,
            'created_at' => $this->created_at,
            'usr_createdt' => $this->usr_createdt,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'usr_updatedt' => $this->usr_updatedt,
            'updated_by' => $this->updated_by,
            'deleted_at' => 0, //$this->deleted_at,
            'usr_deletedt' => $this->usr_deletedt,
            'deleted_by' => $this->deleted_by,
        ]);

        //$userlevelOperator = (is_array($this->usr_sitelevel) ? 'in' : '=');

        $query->andFilterWhere(['like', 'usr_language', $this->usr_language])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'usr_password', $this->usr_password])
            ->andFilterWhere(['like', 'usr_2fasecret', $this->usr_2fasecret])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'usr_firstname', $this->usr_firstname])
            ->andFilterWhere(['like', 'usr_lastname', $this->usr_lastname])
            ->andFilterWhere(['like', 'usr_countrycode', $this->usr_countrycode])
            ->andFilterWhere(['=', 'usr_sitelevel', $this->usr_sitelevel])
						->andFilterWhere(['not in', 'usr_sitelevel', GeneralHelper::minimalSitelevels(BackendUser::USR_SITELEVEL_HFTMEMBER)])
            ->andFilterWhere(['like', 'usr_sitecsstheme', $this->usr_sitecsstheme])
            ->andFilterWhere(['like', 'usr_moralisid', $this->usr_moralisid])
            ->andFilterWhere(['like', 'usr_discordusername', $this->usr_discordusername])
            ->andFilterWhere(['like', 'usr_discordnick', $this->usr_discordnick])
            ->andFilterWhere(['like', 'usr_discordroles', $this->usr_discordroles])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token])
            ->andFilterWhere(['like', 'usr_remarks', $this->usr_remarks]);

return $dataProvider;
}
}

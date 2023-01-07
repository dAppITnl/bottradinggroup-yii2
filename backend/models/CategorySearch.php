<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Category;
use backend\models\User;
use common\helpers\GeneralHelper;

/**
* CategorySearch represents the model behind the search form about `backend\models\Category`.
*/
class CategorySearch extends Category
{
	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
			[['id', 'cat_lock', 'cat_createdat', 'catusr_created_id', 'cat_updatedat', 'catusr_updated_id', 'cat_deletedat', 'catusr_deleted_id'], 'integer'],
			[['cat_type', 'cat_title', 'cat_description', 'cat_createdt', 'cat_updatedt', 'cat_deletedt', 'cat_remarks'], 'safe'],
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
		$query = Category::find();

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
			'cat_lock' => $this->cat_lock,
			'cat_createdat' => $this->cat_createdat,
			'catusr_created_id' => $this->catusr_created_id,
			'cat_updatedat' => $this->cat_updatedat,
			'catusr_updated_id' => $this->catusr_updated_id,
			'cat_deletedat' => ((GeneralHelper::allowWhenMinimal( User::USR_SITELEVEL_SUPERADMIN ) == 'true') ? $this->cat_deletedat : 0),
			'catusr_deleted_id' => $this->catusr_deleted_id,
			'cat_createdt' => $this->cat_createdt,
			'cat_updatedt' => $this->cat_updatedt,
			'cat_deletedt' => $this->cat_deletedt,
		]);

		$query->andFilterWhere(['like', 'cat_type', $this->cat_type])
					->andFilterWhere(['like', 'cat_title', $this->cat_title])
					->andFilterWhere(['like', 'cat_description', $this->cat_description])
					->andFilterWhere(['like', 'cat_remarks', $this->cat_remarks]);

		return $dataProvider;
	}
}

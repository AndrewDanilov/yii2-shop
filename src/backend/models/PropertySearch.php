<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Property;
use andrewdanilov\shop\common\models\CategoryProperties;
use andrewdanilov\shop\common\models\PropertyGroups;

/**
 * Class PropertySearch
 */
class PropertySearch extends Property
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'order', 'category_ids', 'group_ids', 'is_filtered'], 'integer'],
			[['type', 'name', 'filter_type', 'unit'], 'string'],
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
		$query = Property::find()
			->leftJoin(CategoryProperties::tableName(), CategoryProperties::tableName() . '.property_id = ' . Property::tableName() . '.id')
			->leftJoin(PropertyGroups::tableName(), PropertyGroups::tableName() . '.property_id = ' . Property::tableName() . '.id')
			->groupBy(Property::tableName() . '.id');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'order' => SORT_ASC,
				],
				'attributes' => [
					'id',
					'name',
					'type',
					'is_filtered',
					'order',
					'filter_type',
					'unit',
				],
			],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			Property::tableName() . '.id' => $this->id,
			Property::tableName() . '.order' => $this->order,
			Property::tableName() . '.type' => $this->type,
			Property::tableName() . '.is_filtered' => $this->is_filtered,
			Property::tableName() . '.filter_type' => $this->filter_type,
			CategoryProperties::tableName() . '.category_id' => $this->category_ids,
			PropertyGroups::tableName() . '.group_id' => $this->group_ids,
		]);

		$query->andFilterWhere(['like', Property::tableName() . '.name', $this->name])
			->andFilterWhere(['like', Property::tableName() . '.unit', $this->unit]);

		return $dataProvider;
	}
}

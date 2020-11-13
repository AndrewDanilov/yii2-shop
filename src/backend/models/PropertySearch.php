<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Property;
use andrewdanilov\shop\common\models\CategoryProperties;
use andrewdanilov\shop\common\models\PropertyGroups;

/**
 * Class PropertySearch
 *
 * @package andrewdanilov\shop\backend\models
 * @property int $category_id
 * @property int $group_id
 */
class PropertySearch extends Property
{
	public $category_id;
	public $group_id;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'order', 'category_id', 'group_id'], 'integer'],
			[['type', 'name'], 'string'],
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
			->leftJoin(PropertyGroups::tableName(), PropertyGroups::tableName() . '.property_id = ' . Property::tableName() . '.id');

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
					'order',
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
			CategoryProperties::tableName() . '.category_id' => $this->category_id,
			PropertyGroups::tableName() . '.group_id' => $this->group_id,
		]);

		$query->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}
}

<?php
namespace andrewdanilov\shop\backend\models;

use andrewdanilov\shop\common\models\Option;
use andrewdanilov\shop\common\models\CategoryOptions;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class OptionSearch
 */
class OptionSearch extends Option
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order', 'category_ids', 'is_filtered'], 'integer'],
	        [['name'], 'string'],
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
        $query = Option::find()
	        ->leftJoin(CategoryOptions::tableName(), CategoryOptions::tableName() . '.option_id = ' . Option::tableName() . '.id')
	        ->groupBy(Option::tableName() . '.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'order' => SORT_ASC,
		        ],
		        'attributes' => [
			        'id',
			        'name',
			        'is_filtered',
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
	        Option::tableName() . '.id' => $this->id,
	        Option::tableName() . '.is_filtered' => $this->is_filtered,
	        Option::tableName() . '.order' => $this->order,
	        CategoryOptions::tableName() . '.category_id' => $this->category_ids,
        ]);

	    $query->andFilterWhere(['like', Option::tableName() . '.name', $this->name]);

	    return $dataProvider;
    }
}

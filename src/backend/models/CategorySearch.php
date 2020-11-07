<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Category;

class CategorySearch extends Category
{
	public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order'], 'integer'],
            [['image', 'name'], 'safe'],
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
        $query = Category::find()->groupBy('id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'order' => SORT_ASC,
		        ],
		        'attributes' => [
			        'id',
			        'image',
			        'name' => [
				        'asc' => ['name' => SORT_ASC],
				        'desc' => ['name' => SORT_DESC],
			        ],
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
	        'id' => $this->id,
	        'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}

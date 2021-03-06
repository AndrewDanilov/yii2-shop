<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Brand;

class BrandSearch extends Brand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order'], 'integer'],
            [['image', 'name', 'link'], 'string'],
            [['is_favorite'], 'boolean'],
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
        $query = Brand::find()->groupBy('id');

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
			        'is_favorite',
			        'order',
			        'link',
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
	        'is_favorite' => $this->is_favorite,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
	        ->andFilterWhere(['like', 'name', $this->name])
	        ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}

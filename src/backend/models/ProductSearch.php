<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\ProductCategories;
use andrewdanilov\shop\common\models\Product;

/**
 * Class ProductSearch
 *
 * @package andrewdanilov\shop\backend\models
 * @property int $category_id
 */
class ProductSearch extends Product
{
	public $category_id;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brand_id', 'category_id', 'is_new', 'is_popular', 'is_action', 'discount'], 'integer'],
            [['price'], 'number'],
	        [['name', 'article'], 'string'],
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
        $query = Product::find()->joinWith(['brand', 'tagRef'])->groupBy('id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'id' => SORT_DESC,
		        ],
		        'attributes' => [
			        'id',
			        'article',
			        'name' => [
				        'asc' => [Product::tableName() . '.name' => SORT_ASC],
				        'desc' => [Product::tableName() . '.name' => SORT_DESC],
			        ],
			        'price',
			        'discount',
			        'is_new',
			        'is_popular',
			        'is_action',
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
	        Product::tableName() . '.id' => $this->id,
	        Product::tableName() . '.brand_id' => $this->brand_id,
	        Product::tableName() . '.price' => $this->price,
	        Product::tableName() . '.discount' => $this->discount,
	        Product::tableName() . '.is_new' => $this->is_new,
	        Product::tableName() . '.is_popular' => $this->is_popular,
	        Product::tableName() . '.is_action' => $this->is_action,
	        ProductCategories::tableName() . '.category_id' => $this->category_id,
        ]);

	    $query->andFilterWhere(['like', Product::tableName() . '.article', $this->article])
		    ->andFilterWhere(['like', Product::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}

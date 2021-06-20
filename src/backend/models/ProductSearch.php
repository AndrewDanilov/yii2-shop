<?php
namespace andrewdanilov\shop\backend\models;

use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\Sticker;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ProductSearch
 *
 * @package andrewdanilov\shop\backend\models
 * @property int $category_id
 * @property int $sticker_id
 */
class ProductSearch extends Product
{
	public $category_id;
	public $sticker_id;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brand_id', 'discount', 'order', 'category_id', 'sticker_id', 'is_stock'], 'integer'],
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
        $query = Product::find()->joinWith(['categories', 'stickers'])->groupBy('id');

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
			        'name',
			        'price',
			        'discount',
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
	        Product::tableName() . '.id' => $this->id,
	        Product::tableName() . '.brand_id' => $this->brand_id,
	        Product::tableName() . '.price' => $this->price,
	        Product::tableName() . '.discount' => $this->discount,
	        Product::tableName() . '.is_stock' => $this->is_stock,
	        Product::tableName() . '.order' => $this->order,
	        Category::tableName() . '.id' => $this->category_id,
	        Sticker::tableName() . '.id' => $this->sticker_id,
        ]);

	    $query->andFilterWhere(['like', Product::tableName() . '.article', $this->article])
		    ->andFilterWhere(['like', Product::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}

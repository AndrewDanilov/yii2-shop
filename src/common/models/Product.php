<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\ShopOptionBehavior;
use yii\db\ActiveQuery;
use yii\helpers\Inflector;

/**
 * This is the model class for table "shop_product".
 *
 * @property int $id
 * @property string $article
 * @property int $brand_id
 * @property float $price
 * @property integer $discount
 * @property bool $is_new
 * @property bool $is_popular
 * @property bool $is_action
 * @property string $name
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $slug
 * @property Brand $brand
 * @property Order[] $orders
 * @property ActiveQuery $tags
 * @property Property[] $availableProperties
 * @property Option[] $availableOptions
 * @property string $categoriesDelimitedString
 * @property array $availableCategoryProperties
 * @property array $availableCategoryOptions
 * @property ProductImages[] $images
 * @property ProductProperties[] $productProperties
 * @property ProductOptions[] $productOptions
 * @property ProductOptions[] $defaultProductOptions
 * @property string|float|int $priceWithMargin
 * @method ActiveQuery getTag()
 */
class Product extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => 'andrewdanilov\behaviors\ImagesBehavior',
				'imagesModelClass' => 'andrewdanilov\shop\common\models\ProductImages',
				'imagesModelRefAttribute' => 'product_id',
			],
			[
				'class' => 'andrewdanilov\behaviors\TagBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductCategories',
				'referenceModelAttribute' => 'product_id',
				'referenceModelTagAttribute' => 'category_id',
				'tagModelClass' => 'andrewdanilov\shop\common\models\Category',
			],
			'properties' => [
				'class' => 'andrewdanilov\behaviors\ShopOptionBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductProperties',
				'referenceModelOptionAttribute' => 'property_id',
				'optionModelClass' => 'andrewdanilov\shop\common\models\Property',
				'createDefaultValues' => true,
			],
			'options' => [
				'class' => 'andrewdanilov\behaviors\ShopOptionBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductOptions',
				'referenceModelOptionAttribute' => 'option_id',
				'optionModelClass' => 'andrewdanilov\shop\common\models\Option',
			],
			[
				'class' => 'andrewdanilov\behaviors\LinkedProductsBehavior',
				'productModelClass' => 'andrewdanilov\shop\common\models\Product',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductRelations',
				'linksModelClass' => 'andrewdanilov\shop\common\models\Relation',
			],
		];
	}

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['name'], 'required'],
	        [['brand_id', 'discount'], 'integer'],
	        [['is_new', 'is_popular', 'is_action'], 'boolean'],
            [['article', 'name', 'seo_title', 'slug'], 'string', 'max' => 255],
            [['price'], 'number'],
            [['price', 'discount', 'is_new', 'is_popular', 'is_action'], 'default', 'value' => 0],
	        [['description', 'seo_description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Артикул',
            'image' => 'Изображение',
            'brand_id' => 'Бренд',
            'price' => 'Цена',
            'discount' => 'Скидка',
            'category_id' => 'Категории',
            'tagIds' => 'Категории',
	        'name' => 'Название',
	        'is_new' => 'Новый',
	        'is_popular' => 'Популярный',
	        'is_action' => 'Акция',
	        'description' => 'Описание',
	        'seo_title' => 'Seo Title',
	        'seo_description' => 'Seo Description',
	        'slug' => 'Seo Url',
	        'marks' => 'Метки',
        ];
    }

    public function getBrand()
    {
    	return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getOrders()
    {
    	return $this->hasMany(Order::class, ['id' => 'order_id'])->viaTable(OrderProducts::tableName(), ['product_id' => 'id']);
    }

    public function getAvailableCategoryProperties()
    {
    	return $this->hasMany(CategoryProperties::class, ['category_id' => 'id'])->via('tag');
    }

    public function getAvailableCategoryOptions()
    {
    	return $this->hasMany(CategoryOptions::class, ['category_id' => 'id'])->via('tag');
    }

    public function getAvailableProperties() {
    	return $this->hasMany(Property::class, ['id' => 'property_id'])->via('availableCategoryProperties');
    }

    public function getAvailableOptions() {
    	return $this->hasMany(Option::class, ['id' => 'option_id'])->via('availableCategoryOptions');
    }

	//////////////////////////////////////////////////////////////////

	public function beforeSave($insert)
	{
		if (empty($this->slug)) {
			$this->slug = Inflector::slug($this->name);
		}
		return parent::beforeSave($insert);
	}

    //////////////////////////////////////////////////////////////////

	/**
	 * @return ProductProperties[]
	 */
	public function getProductProperties()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('properties');
		return $behavior->getOptionsRef()->all();
	}

	/**
	 * @param null|string|array $productOptionsIds
	 * @return ProductOptions[]
	 */
	public function getProductOptions($productOptionsIds=null)
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('options');
		$productOptions = $behavior->getOptionsRef();
		if ($productOptionsIds !== null) {
			if (!is_array($productOptionsIds)) {
				$productOptionsIds = explode(',', $productOptionsIds);
			}
			$productOptions->where(['id' => $productOptionsIds]);
		}
		return $productOptions->all();
	}

	/**
	 * @return ProductOptions[]
	 */
	public function getDefaultProductOptions()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('options');
		return $behavior->getOptionsRef()->orderBy(ProductOptions::tableName() . '.id')->groupBy('option_id')->all();
	}

    //////////////////////////////////////////////////////////////////

	public function getPriceWithOptions($productOptionsIds=null)
	{
		$price = $this->price;
		if ($productOptionsIds === null) {
			$productOptions = $this->defaultProductOptions;
		} else {
			$productOptions = $this->getProductOptions($productOptionsIds);
		}
		foreach ($productOptions as $productOption) {
			$price += $productOption->add_price;
		}
		return $price;
	}

    //////////////////////////////////////////////////////////////////

    public function getCategoriesDelimitedString()
    {
    	$allCategories = Category::getCategoriesList();
    	$categories = $this->getTag()->select('id')->indexBy('id')->column();
    	$categories = array_intersect_key($allCategories, $categories);
    	return implode(', ', $categories);
    }

    public function getMarksDelimitedString()
    {
    	$allMarks = static::getMarksList();
    	$marks = [];
	    foreach ($allMarks as $mark_key => $mark_value) {
		    if ($this->$mark_key) {
		    	$marks[] = $mark_value;
		    }
    	}
    	return implode(', ', $marks);
    }

	//////////////////////////////////////////////////////////////////

	public static function getMarksList()
    {
    	return [
    		'is_new' => 'Новинка',
		    'is_popular' => 'Популярный',
		    'is_action' => 'Акция',
	    ];
    }
}

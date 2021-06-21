<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\TagBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use andrewdanilov\behaviors\ShopOptionBehavior;
use andrewdanilov\behaviors\ValueTypeBehavior;

/**
 * This is the model class for table "shop_product".
 *
 * @property int $id
 * @property string $article
 * @property int $brand_id
 * @property float $price
 * @property integer $discount
 * @property string $name
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $slug
 * @property integer $order
 * @property integer $is_stock
 * @property string $meta_data // todo: use for custom category fields
 * @property Brand $brand
 * @property Order[] $orders
 * @property Property[] $availableProperties
 * @property Option[] $availableOptions
 * @property integer[] $category_ids
 * @property Category[] $categories
 * @property integer[] $sticker_ids
 * @property Sticker[] $stickers
 * @property string $categoriesDelimitedString
 * @property array $availableCategoryProperties
 * @property array $availableCategoryOptions
 * @property ProductImages[] $images
 * @property ProductProperties[] $productProperties
 * @property ProductOptions[] $productOptions
 * @property ProductOptions[] $defaultProductOptions
 * @property string|float|int $priceWithOptions
 */
class Product extends \yii\db\ActiveRecord
{
	public $category_ids;
	public $sticker_ids;

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
			'categories' => [
				'class' => 'andrewdanilov\behaviors\TagBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductCategories',
				'referenceModelAttribute' => 'product_id',
				'referenceModelTagAttribute' => 'category_id',
				'tagModelClass' => 'andrewdanilov\shop\common\models\Category',
				'ownerModelIdsAttribute' => 'category_ids',
			],
			'stickers' => [
				'class' => 'andrewdanilov\behaviors\TagBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductStickers',
				'referenceModelAttribute' => 'product_id',
				'referenceModelTagAttribute' => 'sticker_id',
				'tagModelClass' => 'andrewdanilov\shop\common\models\Sticker',
				'ownerModelIdsAttribute' => 'sticker_ids',
			],
			'properties' => [
				'class' => 'andrewdanilov\behaviors\ShopOptionBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductProperties',
				'referenceModelOptionAttribute' => 'property_id',
				'optionModelClass' => 'andrewdanilov\shop\common\models\Property',
				'optionModelOrderAttribute' => 'order',
				'createDefaultValues' => true,
			],
			'options' => [
				'class' => 'andrewdanilov\behaviors\ShopOptionBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\ProductOptions',
				'referenceModelOptionAttribute' => 'option_id',
				'optionModelClass' => 'andrewdanilov\shop\common\models\Option',
				'optionModelOrderAttribute' => 'order',
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
			[['article', 'name', 'seo_title', 'slug'], 'string', 'max' => 255],
			[['price'], 'number'],
			[['price', 'discount'], 'default', 'value' => 0],
			[['description', 'seo_description', 'meta_data'], 'string'],
			[['order'], 'integer'],
			[['order'], 'default', 'value' => 500],
			[['is_stock'], 'default', 'value' => 1],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'article' => Yii::t('shop/common', 'SKU'),
			'image' => Yii::t('shop/common', 'Image'),
			'brand_id' => Yii::t('shop/common', 'Brand'),
			'price' => Yii::t('shop/common', 'Price'),
			'discount' => Yii::t('shop/common', 'Discount'),
			'category_id' => Yii::t('shop/common', 'Categories'),
			'category_ids' => Yii::t('shop/common', 'Categories'),
			'categories' => Yii::t('shop/common', 'Categories'),
			'sticker_id' => Yii::t('shop/common', 'Stickers'),
			'sticker_ids' => Yii::t('shop/common', 'Stickers'),
			'stickers' => Yii::t('shop/common', 'Stickers'),
			'name' => Yii::t('shop/common', 'Name'),
			'description' => Yii::t('shop/common', 'Description'),
			'seo_title' => Yii::t('shop/common', 'Seo Title'),
			'seo_description' => Yii::t('shop/common', 'Seo Description'),
			'slug' => Yii::t('shop/common', 'Seo Url'),
			'order' => Yii::t('shop/common', 'Order'),
			'is_stock' => Yii::t('shop/common', 'Available in stock'),
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
			if (empty($this->slug)) {
				$this->slug = 'product-' . date('YmdHis');
			}
		}
		return parent::beforeSave($insert);
	}

	//////////////////////////////////////////////////////////////////

	/**
	 * @return ProductProperties[]|ValueTypeBehavior[]
	 */
	public function getProductProperties()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('properties');
		$behavior->optionsFilter = ArrayHelper::map($this->availableProperties, 'id', 'id');
		$productProperties = $behavior->getOptionsRef();
		return $productProperties->all();
	}

	/**
	 * @param string $group_code
	 * @return ProductProperties[]|ValueTypeBehavior[]
	 */
	public function getGroupProductProperties($group_code, $show_empty=true)
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('properties');
		$behavior->optionsFilter = ArrayHelper::map($this->availableProperties, 'id', 'id');
		$productProperties = $behavior->getOptionsRef();
		$productProperties->innerJoin(PropertyGroups::tableName(), PropertyGroups::tableName() . '.property_id = ' . ProductProperties::tableName() . '.property_id');
		$productProperties->innerJoin(Group::tableName(), Group::tableName() . '.id = ' . PropertyGroups::tableName() . '.group_id');
		$productProperties->andWhere([Group::tableName() . '.code' => $group_code]);
		if (!$show_empty) {
			$productProperties->andWhere(['not', [ProductProperties::tableName() . '.value' => '']]);
		}
		return $productProperties->all();
	}

	/**
	 * @return ProductProperties[]|ValueTypeBehavior[]
	 */
	public function getFilteredProductProperties()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('properties');
		$behavior->optionsFilter = ArrayHelper::map($this->availableProperties, 'id', 'id');
		$productProperties = $behavior->getOptionsRef();
		$productProperties->andWhere(['is_filtered' => 1]);
		return $productProperties->all();
	}

	/**
	 * @param array|string|null $productOptionsIds
	 * @return ProductOptions[]
	 */
	public function getProductOptions($productOptionsIds=null)
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('options');
		$behavior->optionsFilter = ArrayHelper::map($this->availableOptions, 'id', 'id');
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
	public function getFilteredProductOptions()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('options');
		$behavior->optionsFilter = ArrayHelper::map($this->availableOptions, 'id', 'id');
		$productOptions = $behavior->getOptionsRef();
		$productOptions->where(['is_filtered' => 1]);
		return $productOptions->all();
	}

	/**
	 * @return ProductOptions[]
	 */
	public function getDefaultProductOptions()
	{
		/* @var $behavior ShopOptionBehavior */
		$behavior = $this->getBehavior('options');
		$behavior->optionsFilter = ArrayHelper::map($this->availableOptions, 'id', 'id');
		return $behavior->getOptionsRef()->groupBy('option_id')->all();
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

	/**
	 * @return ActiveQuery
	 */
	public function getCategories()
	{
		$behavior = $this->getBehavior('categories');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTags();
		}
		return null;
	}

	/**
	 * @return ActiveQuery
	 */
	public function getStickers()
	{
		$behavior = $this->getBehavior('stickers');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTags();
		}
		return null;
	}

	/**
	 * @return string
	 */
	public function getCategoriesDelimitedString()
	{
		$allCategories = Category::getCategoriesList();
		$categories = array_intersect_key($allCategories, array_flip($this->category_ids));
		return implode(', ', $categories);
	}

	/**
	 * @return string
	 */
	public function getStickersDelimitedString()
	{
		$allStickers = Sticker::getStickersList();
		$stickers = array_intersect_key($allStickers, array_flip($this->sticker_ids));
		return implode(', ', $stickers);
	}
}

<?php
namespace andrewdanilov\shop\common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Inflector;
use andrewdanilov\helpers\NestedCategoryHelper;

/**
 * This is the model class for table "shop_category".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $image
 * @property int $order
 * @property string $name
 * @property string $short_description
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $slug
 * @property string $meta_data // todo: use for custom category fields
 * @property Category[] $children
 * @property Product[] $products
 * @property ProductCategories[] $allChildrenProductCategories
 * @property Product[] $allChildrenProducts
 * @property Property[] $properties
 * @property Option[] $options
 * @property Brand[] $brands
 */
class Category extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'shop_category';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name'], 'required'],
			[['order', 'parent_id'], 'integer'],
			[['parent_id'], 'validateParent'],
			[['name', 'seo_title', 'image', 'slug'], 'string', 'max' => 255],
			[['short_description', 'description', 'seo_description', 'meta_data'], 'string'],
			[['order', 'parent_id'], 'default', 'value' => 0],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'parent_id' => Yii::t('shop/common', 'Parent category'),
			'image' => Yii::t('shop/common', 'Cover'),
			'order' => Yii::t('shop/common', 'Order'),
			'name' => Yii::t('shop/common', 'Name'),
			'short_description' => Yii::t('shop/common', 'Short description'),
			'description' => Yii::t('shop/common', 'Description'),
			'seo_title' => Yii::t('shop/common', 'Seo Title'),
			'seo_description' => Yii::t('shop/common', 'Seo Description'),
			'slug' => Yii::t('shop/common', 'Seo Url'),
		];
	}

	public function validateParent($attribute, $params, $validator)
	{
		if ($this->$attribute == $this->id) {
			$this->addError($attribute, Yii::t('shop/common', 'Category cannot be nested in itself'));
		}
	}

	//////////////////////////////////////////////////////////////////

	public function getChildren()
	{
		return $this->hasMany(Category::class, ['parent_id' => 'id']);
	}

	/**
	 * Products from a category
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductCategories::tableName(), ['category_id' => 'id']);
	}

	/**
	 * ProductCategories link for products from current category and from all children categories
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getAllChildrenProductCategories()
	{
		$children_ids = NestedCategoryHelper::getChildrenIds(Category::find(), $this->id);
		array_push($children_ids, $this->id);
		$query = ProductCategories::find();
		$query->where(['category_id' => $children_ids]);
		$query->multiple = true;
		return $query;
	}

	/**
	 * Products link for products from current category and from all children categories
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getAllChildrenProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->via('allChildrenProductCategories');
	}

	public function getBrands()
	{
		return $this->hasMany(Brand::class, ['id' => 'brand_id'])->via('products');
	}

	public function getOptions()
	{
		return $this->hasMany(Option::class, ['id' => 'option_id'])->viaTable(CategoryOptions::tableName(), ['category_id' => 'id']);
	}

	public function getProperties()
	{
		return $this->hasMany(Property::class, ['id' => 'property_id'])->viaTable(CategoryProperties::tableName(), ['category_id' => 'id']);
	}

	//////////////////////////////////////////////////////////////////

	public function beforeSave($insert)
	{
		if (empty($this->slug)) {
			$this->slug = Inflector::slug($this->name);
			if (empty($this->slug)) {
				$this->slug = 'category-' . date('YMDHis');
			}
		}
		return parent::beforeSave($insert);
	}

	//////////////////////////////////////////////////////////////////

	public static function getCategoriesList()
	{
		return self::find()->select(['name', 'id'])->orderBy('order')->indexBy('id')->column();
	}

	public static function getParentCategoriesList()
	{
		return self::find()->select(['name', 'id'])->where(['parent_id' => 0])->orderBy('order')->indexBy('id')->column();
	}

	/**
	 * Дополняет массив ID's категорий родительскими категориями
	 * по цепочке до самой верхней родительской категории для
	 * каждой из указанных в $category_ids дочерней категории
	 *
	 * @param int[] $category_ids
	 * @return int[]
	 */
	public static function addParentCategoryIds($category_ids)
	{
		$category_ids = array_unique($category_ids);
		$available_category_ids = [];
		$allCategories = Category::find()->asArray()->all();
		foreach ($category_ids as $category_id) {
			$available_category_ids = array_merge($available_category_ids, NestedCategoryHelper::getCategoryPathIds($allCategories, $category_id));
		}
		return array_unique($available_category_ids);
	}

	/**
	 * Returns all possible values for all properties of products in the specified category.
	 * Only properties with the is_filtered flag are returned.
	 * Result as array:
	 * [
	 *   '1' => [
	 *     'name' => 'Property 1 Name',
	 *     'values' => ['value 1', 'value 2'],
	 *     'type' => 'string',
	 *     'filter_type' => 'checkboxes',
	 *   ]
	 *   '2' => [
	 *     'name' => 'Property 2 Name',
	 *     'values' => ['value 1', 'value 2'],
	 *     'type' => 'integer',
	 *     'filter_type' => 'interval',
	 *   ]
	 * ]
	 *
	 * @param int $category_id
	 * @return array
	 */
	public static function getCategoryProductFilteredPropertyValues($category_id=0, $sort_by_value=false)
	{
		// все свойства из категории и РОДИТЕЛЬСКИХ категорий
		$available_property_ids = CategoryProperties::getAvailablePropertyIds([$category_id]);

		// категория плюс все ДОЧЕРНИЕ категории для выборки товаров - необходимо,
		// чтобы узнать мин/макс значения свойств нужные для свойств в фильтре типа диапазонов
		$category_ids = NestedCategoryHelper::getChildrenIds(Category::find(), $category_id);
		if ($category_id) {
			array_push($category_ids, $category_id);
		}

		$category_product_properties_query = (new Query())
			->select([
				ProductProperties::tableName() . '.property_id',
				ProductProperties::tableName() . '.value',
				Property::tableName() . '.name',
				Property::tableName() . '.type',
				Property::tableName() . '.filter_type',
			])
			->from(ProductCategories::tableName())
			->innerJoin(ProductProperties::tableName(), ProductProperties::tableName() . '.product_id = ' . ProductCategories::tableName() . '.product_id')
			->innerJoin(Property::tableName(), Property::tableName() . '.id = ' . ProductProperties::tableName() . '.property_id')
			->andWhere([ProductCategories::tableName() . '.category_id' => $category_ids])
			->andWhere([Property::tableName() . '.is_filtered' => 1])
			->andWhere([Property::tableName() . '.id' => $available_property_ids])
			->groupBy(ProductProperties::tableName() . '.id')
			->orderBy([Property::tableName() . '.order' => SORT_ASC]);
		if ($sort_by_value) {
			$category_product_properties_query->addOrderBy([ProductProperties::tableName() . '.value' => SORT_ASC]);
		}

		$category_product_properties = $category_product_properties_query->all();

		$filtered_properties = [];
		foreach ($category_product_properties as $property) {
			if (!isset($filtered_properties[$property['property_id']])) {
				$filtered_properties[$property['property_id']]['name'] = $property['name'];
				$filtered_properties[$property['property_id']]['type'] = $property['type'];
				$filtered_properties[$property['property_id']]['filter_type'] = $property['filter_type'];
			}
			$filtered_properties[$property['property_id']]['values'][md5($property['value'])] = $property['value'];
		}

		return $filtered_properties;
	}
}

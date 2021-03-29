<?php
namespace andrewdanilov\shop\common\models;

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
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $slug
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
			[['description', 'seo_description'], 'string'],
			[['order', 'parent_id'], 'default', 'value' => 0],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'parent_id' => 'Родительская категория',
			'image' => 'Обложка',
			'order' => 'Порядок',
			'name' => 'Название',
			'description' => 'Описание',
			'seo_title' => 'Seo Title',
			'seo_description' => 'Seo Description',
			'slug' => 'Seo Url',
		];
	}

	public function validateParent($attribute, $params, $validator)
	{
		if ($this->$attribute == $this->id) {
			$this->addError($attribute, 'Категория не может быть вложена сама в себя');
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
				$this->slug = 'category-' . $this->id;
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
	 * Возвращает все возможные значения всех свойств товаров указанной категории.
	 * Возвращаются только свойства имеющие признак is_filtered.
	 * Результат в виде массива:
	 * [
	 *   '1' => [
	 *     'name' => 'Property 1 Name',
	 *     'values' => ['value 1', 'value 2'],
	 *   ]
	 *   '2' => [
	 *     'name' => 'Property 2 Name',
	 *     'values' => ['value 1', 'value 2'],
	 *   ]
	 * ]
	 *
	 * @param int $category_id
	 * @return array
	 */
	public static function getCategoryProductFilteredPropertyValues($category_id=0, $hide_empty=false, $sort_by_value=false)
	{
		$category_ids = NestedCategoryHelper::getChildrenIds(Category::find(), $category_id);
		if ($category_id) {
			array_push($category_ids, $category_id);
		}

		$category_product_properties_query = (new Query())
			->select([
				ProductProperties::tableName() . '.property_id',
				ProductProperties::tableName() . '.value',
				Property::tableName() . '.name',
			])
			->from(ProductCategories::tableName())
			->innerJoin(ProductProperties::tableName(), ProductProperties::tableName() . '.product_id = ' . ProductCategories::tableName() . '.product_id')
			->innerJoin(Property::tableName(), Property::tableName() . '.id = ' . ProductProperties::tableName() . '.property_id')
			->andWhere([ProductCategories::tableName() . '.category_id' => $category_ids])
			->andWhere([Property::tableName() . '.is_filtered' => 1])
			->groupBy(ProductProperties::tableName() . '.id')
			->orderBy([Property::tableName() . '.order' => SORT_ASC]);
		if ($hide_empty) {
			$category_product_properties_query->andWhere(['not', [ProductProperties::tableName() . '.value' => '']]);
		}
		if ($sort_by_value) {
			$category_product_properties_query->addOrderBy([ProductProperties::tableName() . '.value' => SORT_ASC]);
		}

		$category_product_properties = $category_product_properties_query->all();

		$filtered_properties = [];
		foreach ($category_product_properties as $property) {
			if (!isset($filtered_properties[$property['property_id']])) {
				$filtered_properties[$property['property_id']]['name'] = $property['name'];
			}
			$filtered_properties[$property['property_id']]['values'][md5($property['value'])] = $property['value'];
		}

		return $filtered_properties;
	}
}

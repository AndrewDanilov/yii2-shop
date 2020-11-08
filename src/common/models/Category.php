<?php
namespace andrewdanilov\shop\common\models;

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
		}
		return parent::beforeSave($insert);
	}

	//////////////////////////////////////////////////////////////////

	public static function getCategoriesList()
	{
		return self::find()->select(['name', 'id'])->indexBy('id')->column();
	}
}

<?php
namespace andrewdanilov\shop\common\models;

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
 * @property Product[] $products
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
            [['image'], 'string', 'max' => 255],
	        [['name', 'seo_title'], 'string', 'max' => 255],
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
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductCategories::tableName(), ['category_id' => 'id']);
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

	public static function getCategoriesList()
	{
		return self::find()->select(['name', 'id'])->indexBy('id')->column();
	}
}

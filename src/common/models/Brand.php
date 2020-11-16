<?php
namespace andrewdanilov\shop\common\models;

use yii\helpers\Inflector;

/**
 * This is the model class for table "shop_brand".
 *
 * @property int $id
 * @property string $image
 * @property int $is_favorite
 * @property int $order
 * @property string $name
 * @property string $description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $slug
 * @property string $link
 * @property Product[] $products
 * @property Category[] $categories
 */
class Brand extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['name'], 'required'],
	        [['name', 'seo_title', 'image', 'slug', 'link'], 'string', 'max' => 255],
	        [['description', 'seo_description'], 'string'],
	        [['order'], 'integer'],
            [['order'], 'default', 'value' => 0],
            [['is_favorite'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Логотип',
            'is_favorite' => 'Избранный',
            'order' => 'Порядок',
            'name' => 'Название',
	        'description' => 'Описание',
	        'seo_title' => 'Seo Title',
	        'seo_description' => 'Seo Description',
	        'slug' => 'Seo Url',
	        'link' => 'Ссылка на сайт',
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['brand_id' => 'id']);
	}

	public function getCategories()
	{
		return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable(Product::tableName(), ['brand_id' => 'id']);
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
	//////////////////////////////////////////////////////////////////

	public static function getBrandsList()
	{
		return self::find()->select(['name', 'id'])->indexBy('id')->column();
	}
}

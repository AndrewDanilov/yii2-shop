<?php
namespace andrewdanilov\shop\common\models;

use Yii;
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
            'id' => Yii::t('shop/common', 'ID'),
            'image' => Yii::t('shop/common', 'Logo'),
            'is_favorite' => Yii::t('shop/common', 'Favorite'),
            'order' => Yii::t('shop/common', 'Order'),
            'name' => Yii::t('shop/common', 'Name'),
	        'description' => Yii::t('shop/common', 'Description'),
	        'seo_title' => Yii::t('shop/common', 'Seo Title'),
	        'seo_description' => Yii::t('shop/common', 'Seo Description'),
	        'slug' => Yii::t('shop/common', 'Seo Url'),
	        'link' => Yii::t('shop/common', 'Link'),
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
			if (empty($this->slug)) {
				$this->slug = 'brand-' . date('YmdHis');
			}
		}
		return parent::beforeSave($insert);
	}

	//////////////////////////////////////////////////////////////////

	public static function getBrandsList()
	{
		return self::find()->select(['name', 'id'])->indexBy('id')->column();
	}
}

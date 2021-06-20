<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\TagBehavior;
use Yii;

/**
 * This is the model class for table "shop_option".
 *
 * @property int $id
 * @property int $order
 * @property string $name
 * @property bool $is_filtered
 * @property Product[] $products
 * @property Category[] $categories
 * @property integer[] $category_ids
 */
class Option extends \yii\db\ActiveRecord
{
	public $category_ids;

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'category' => [
				'class' => TagBehavior::class,
				'referenceModelClass' => CategoryOptions::class,
				'referenceModelAttribute' => 'option_id',
				'referenceModelTagAttribute' => 'category_id',
				'tagModelClass' => Category::class,
				'ownerModelIdsAttribute' => 'category_ids',
			],
		];
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['name'], 'required'],
            [['order', 'is_filtered'], 'integer'],
	        [['name'], 'string', 'max' => 255],
	        [['order', 'is_filtered'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'order' => Yii::t('shop/common', 'Order'),
	        'name' => Yii::t('shop/common', 'Name'),
	        'category_ids' => Yii::t('shop/common', 'Categories'),
	        'is_filtered' => Yii::t('shop/common', 'Use in filter')
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductOptions::tableName(), ['option_id' => 'id']);
	}

	public function getCategories()
	{
		$behavior = $this->getBehavior('category');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTags();
		}
		return null;
	}

	//////////////////////////////////////////////////////////////////

	public function beforeDelete()
	{
		$this->unlinkAll('products', true);
		return parent::beforeDelete();
	}

	//////////////////////////////////////////////////////////////////

	public function categoriesDelimitedString()
	{
		$allCategories = Category::getCategoriesList();
		$categories = array_intersect_key($allCategories, array_flip($this->category_ids));
		return implode(', ', $categories);
	}
}

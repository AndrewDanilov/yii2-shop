<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\TagBehavior;

/**
 * This is the model class for table "shop_option".
 *
 * @property int $id
 * @property int $order
 * @property string $name
 * @property bool $is_filtered
 * @property Product[] $products
 * @property Category[] $categories
 */
class Option extends \yii\db\ActiveRecord
{
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
            'id' => 'ID',
            'order' => 'Порядок',
	        'name' => 'Название',
	        'categoryIds' => 'Категории',
	        'groupIds' => 'Группы свойств',
	        'is_filtered' => 'Использовать в фильтре',
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
			return $behavior->getTag();
		}
		return null;
	}

	//////////////////////////////////////////////////////////////////

	public function getCategoryIds()
	{
		$behavior = $this->getBehavior('category');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTagIds();
		}
		return [];
	}

	public function setCategoryIds($ids)
	{
		$behavior = $this->getBehavior('category');
		if ($behavior instanceof TagBehavior) {
			return $behavior->setTagIds($ids);
		}
		return [];
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
		$categories = $this->getCategoryIds();
		$categories = array_intersect_key($allCategories, array_flip($categories));
		return implode(', ', $categories);
	}
}

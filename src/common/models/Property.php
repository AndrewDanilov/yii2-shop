<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\TagBehavior;

/**
 * This is the model class for table "shop_property".
 *
 * @property int $id
 * @property string $type
 * @property int $order
 * @property string $name
 * @property Product[] $products
 * @property Category[] $categories
 * @property Group[] $groups
 */
class Property extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'category' => [
				'class' => TagBehavior::class,
				'referenceModelClass' => CategoryProperties::class,
				'referenceModelAttribute' => 'property_id',
				'referenceModelTagAttribute' => 'category_id',
				'tagModelClass' => Category::class,
			],
			'group' => [
				'class' => TagBehavior::class,
				'referenceModelClass' => PropertyGroups::class,
				'referenceModelAttribute' => 'property_id',
				'referenceModelTagAttribute' => 'group_id',
				'tagModelClass' => Group::class,
			],
		];
	}

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['order'], 'integer'],
	        [['name'], 'string', 'max' => 255],
	        [['order'], 'default', 'value' => 0],
	        [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'order' => 'Порядок',
            'name' => 'Название',
            'categoryIds' => 'Категории',
            'groupIds' => 'Группы свойств',
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductProperties::tableName(), ['property_id' => 'id']);
	}

	public function getCategories()
	{
		return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable(CategoryProperties::tableName(), ['property_id' => 'id']);
	}

	public function getGroups()
	{
		return $this->hasMany(Group::class, ['id' => 'group_id'])->viaTable(PropertyGroups::tableName(), ['property_id' => 'id']);
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

	public function getGroupIds()
	{
		$behavior = $this->getBehavior('group');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTagIds();
		}
		return [];
	}

	public function setGroupIds($ids)
	{
		$behavior = $this->getBehavior('group');
		if ($behavior instanceof TagBehavior) {
			return $behavior->setTagIds($ids);
		}
		return [];
	}

	//////////////////////////////////////////////////////////////////

	public function beforeDelete()
	{
		$this->unlinkAll('products', true);
		$this->unlinkAll('categories', true);
		$this->unlinkAll('groups', true);
		return parent::beforeDelete();
	}
}

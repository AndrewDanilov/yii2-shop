<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\behaviors\TagBehavior;
use Yii;

/**
 * This is the model class for table "shop_property".
 *
 * @property int $id
 * @property string $type
 * @property int $order
 * @property string $name
 * @property bool $is_filtered
 * @property string $filter_type
 * @property string $unit
 * @property Product[] $products
 * @property Category[] $categories
 * @property integer[] $category_ids
 * @property Group[] $groups
 * @property integer[] $group_ids
 */
class Property extends \yii\db\ActiveRecord
{
	const FILTER_TYPE_CHECKBOXES = 'checkboxes';
	const FILTER_TYPE_LIST = 'list';
	const FILTER_TYPE_INTERVAL = 'interval';

	public $category_ids;
	public $group_ids;

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
				'ownerModelIdsAttribute' => 'category_ids',
			],
			'group' => [
				'class' => TagBehavior::class,
				'referenceModelClass' => PropertyGroups::class,
				'referenceModelAttribute' => 'property_id',
				'referenceModelTagAttribute' => 'group_id',
				'tagModelClass' => Group::class,
				'ownerModelIdsAttribute' => 'group_ids',
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
			[['order', 'is_filtered'], 'integer'],
			[['name', 'filter_type', 'unit'], 'string', 'max' => 255],
			[['order', 'is_filtered'], 'default', 'value' => 0],
			[['type'], 'string', 'max' => 10],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'type' => Yii::t('shop/common', 'Type'),
			'order' => Yii::t('shop/common', 'Order'),
			'name' => Yii::t('shop/common', 'Name'),
			'category_ids' => Yii::t('shop/common', 'Categories'),
			'group_ids' => Yii::t('shop/common', 'Property groups'),
			'is_filtered' => Yii::t('shop/common', 'Use in filter'),
			'filter_type' => Yii::t('shop/common', 'Filter type'),
			'unit' => Yii::t('shop/common', 'Unit of measure'),
		];
	}

	//////////////////////////////////////////////////////////////////

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductProperties::tableName(), ['property_id' => 'id']);
	}

	public function getCategories()
	{
		$behavior = $this->getBehavior('category');
		if ($behavior instanceof TagBehavior) {
			return $behavior->getTags();
		}
		return null;
	}

	public function getGroups()
	{
		$behavior = $this->getBehavior('group');
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

	public function groupsDelimitedString()
	{
		$allGroups = Group::getGroupList();
		$groups = array_intersect_key($allGroups, array_flip($this->group_ids));
		return implode(', ', $groups);
	}
	
	//////////////////////////////////////////////////////////////////
	
	public static function getFilterTypes()
	{
		return [
			self::FILTER_TYPE_CHECKBOXES => Yii::t('shop/common', 'Checkboxes'),
			self::FILTER_TYPE_LIST => Yii::t('shop/common', 'List'),
			self::FILTER_TYPE_INTERVAL => Yii::t('shop/common', 'Interval'),
		];
	}
}

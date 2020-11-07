<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_property".
 *
 * @property int $id
 * @property string $type
 * @property int $order
 * @property string $name
 * @property Product[] $products
 */
class Property extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => 'andrewdanilov\behaviors\TagBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\CategoryProperties',
				'referenceModelAttribute' => 'property_id',
				'referenceModelTagAttribute' => 'category_id',
				'tagModelClass' => 'andrewdanilov\shop\common\models\Category',
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
            'tagIds' => 'Категории',
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductProperties::tableName(), ['property_id' => 'id']);
	}

	//////////////////////////////////////////////////////////////////

	public function beforeDelete()
	{
		$this->unlinkAll('products', true);
		return parent::beforeDelete();
	}
}

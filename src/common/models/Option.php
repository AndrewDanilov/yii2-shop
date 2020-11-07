<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_option".
 *
 * @property int $id
 * @property int $order
 * @property string $name
 * @property Product[] $products
 */
class Option extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => 'andrewdanilov\behaviors\TagBehavior',
				'referenceModelClass' => 'andrewdanilov\shop\common\models\CategoryOptions',
				'referenceModelAttribute' => 'option_id',
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
        return 'shop_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['name'], 'required'],
            [['order'], 'integer'],
	        [['name'], 'string', 'max' => 255],
	        [['order'], 'default', 'value' => 0],
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
	        'tagIds' => 'Категории',
        ];
    }

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductOptions::tableName(), ['option_id' => 'id']);
	}

	//////////////////////////////////////////////////////////////////

	public function beforeDelete()
	{
		$this->unlinkAll('products', true);
		return parent::beforeDelete();
	}
}

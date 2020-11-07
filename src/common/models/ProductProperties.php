<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_product_properties".
 *
 * @property int $id
 * @property int $product_id
 * @property int $property_id
 * @property string $value
 * @property Property $property
 */
class ProductProperties extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => 'andrewdanilov\behaviors\ValueTypeBehavior',
				'typeAttribute' => 'property.type',
			],
		];
	}

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'product_id', 'value'], 'required'],
            [['property_id', 'product_id'], 'integer'],
	        [['value'], 'string'],
	        [['property_id', 'product_id'], 'unique', 'targetAttribute' => ['property_id', 'product_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_id' => 'Property ID',
            'product_id' => 'Product ID',
	        'value' => 'Value',
        ];
    }

    public function getProperty()
    {
    	return $this->hasOne(Property::class, ['id' => 'property_id']);
    }
}

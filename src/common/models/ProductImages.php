<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_brand".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image
 * @property int $order
 * @property Product $product
 */
class ProductImages extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['product_id', 'image'], 'required'],
	        [['product_id', 'order'], 'integer'],
	        [['image'], 'string', 'max' => 255],
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
            'product_id' => 'Товар',
            'image' => 'Изображение',
        ];
    }

	public function getProduct()
	{
		return $this->hasOne(Product::class, ['id' => 'product_id']);
	}
}

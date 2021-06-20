<?php
namespace andrewdanilov\shop\common\models;

use Yii;

/**
 * This is the model class for table "shop_product_options".
 *
 * @property int $id
 * @property int $option_id
 * @property int $product_id
 * @property float $add_price
 * @property string $value
 * @property Option $option
 */
class ProductOptions extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'product_id', 'value'], 'required'],
            [['option_id', 'product_id'], 'integer'],
            [['add_price'], 'number'],
            [['value'], 'string', 'max' => 255],
            [['add_price'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'option_id' => Yii::t('shop/common', 'Option'),
            'product_id' => Yii::t('shop/common', 'Product'),
            'add_price' => Yii::t('shop/common', 'Add price'),
            'value' => Yii::t('shop/common', 'Value'),
        ];
    }

	public function getOption()
	{
		return $this->hasOne(Option::class, ['id' => 'option_id']);
	}
}

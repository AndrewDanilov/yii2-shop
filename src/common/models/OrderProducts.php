<?php
namespace andrewdanilov\shop\common\models;

use Yii;

/**
 * This is the model class for table "shop_order_products".
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $name
 * @property float $price
 * @property int $count
 * @property Order $order
 * @property Product $product
 * @property OrderProductsOptions[] $orderProductsOptions
 * @property ProductOptions[] $productOptions
 * @property Option[] $options
 * @property string $productOptionsStr
 */
class OrderProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'name', 'price', 'count'], 'required'],
            [['order_id', 'product_id', 'count'], 'integer'],
            [['price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'order_id' => Yii::t('shop/common', 'Order num'),
            'product_id' => Yii::t('shop/common', 'Product ID'),
            'product_option_id' => Yii::t('shop/common', 'Option ID'),
            'name' => Yii::t('shop/common', 'Name'),
            'price' => Yii::t('shop/common', 'Price'),
            'count' => Yii::t('shop/common', 'Count'),
            'image' => Yii::t('shop/common', 'Image'),
            'option' => Yii::t('shop/common', 'Options'),
            'summ' => Yii::t('shop/common', 'Sum'),
        ];
    }

    public function getOrder()
    {
    	return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduct()
    {
    	return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getOrderProductsOptions()
    {
    	return $this->hasMany(OrderProductsOptions::class, ['order_product_id' => 'id']);
    }

    public function getProductOptions()
    {
    	return $this->hasMany(ProductOptions::class, ['id' => 'product_option_id'])->via('orderProductsOptions');
    }

    public function getOptions()
    {
    	return $this->hasMany(Option::class, ['id' => 'option_id'])->via('productOptions');
    }

    //////////////////////////////////////////////////////////////////

    public function beforeDelete()
    {
	    foreach ($this->orderProductsOptions as $model) {
		    $model->delete();
	    }
	    return parent::beforeDelete();
    }

    //////////////////////////////////////////////////////////////////

    public function getProductOptionsStr()
    {
	    $product_options = $this->productOptions;
	    $html = [];
	    foreach ($product_options as $product_option) {
		    $option = $product_option->option;
		    $html[] = $option->name . ': ' . $product_option->value;
	    }
	    return implode('<br/>', $html);
    }
}

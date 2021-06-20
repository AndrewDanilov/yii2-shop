<?php
namespace andrewdanilov\shop\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class ProductStickers
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $sticker_id
 */
class ProductStickers extends ActiveRecord
{
	public static function tableName()
	{
		return 'shop_product_stickers';
	}

	public function rules()
	{
		return [
			[['product_id', 'sticker_id'], 'required'],
			[['product_id', 'sticker_id'], 'unique', 'targetAttribute' => ['product_id', 'sticker_id']],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'product_id' => Yii::t('shop/common', 'Product'),
			'sticker_id' => Yii::t('shop/common', 'Sticker'),
		];
	}
}
<?php
namespace andrewdanilov\shop\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Sticker
 *
 * @property integer $id
 * @property string $label
 * @property string $image
 * @property integer $order
 */
class Sticker extends ActiveRecord
{
	public static function tableName()
	{
		return 'shop_sticker';
	}

	public function rules()
	{
		return [
			[['label'], 'required'],
			[['image'], 'string'],
			[['order'], 'integer'],
			[['order'], 'default', 'value' => 0],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'label' => Yii::t('shop/common', 'Name'),
			'image' => Yii::t('shop/common', 'Image'),
			'order' => Yii::t('shop/common', 'Order'),
		];
	}

	public function getProducts()
	{
		return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable(ProductStickers::tableName(), ['sticker_id' => 'id']);
	}

	public function beforeDelete()
	{
		$this->unlinkAll('products', true);
		return parent::beforeDelete();
	}

	public static function getStickersList()
	{
		return static::find()->select(['label', 'id'])->orderBy('order')->asArray()->indexBy('id')->column();
	}
}
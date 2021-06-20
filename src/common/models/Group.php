<?php
namespace andrewdanilov\shop\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Group
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property int $order
 * @property Property[] $properties
 * @property Option[] $options
 */
class Group extends ActiveRecord
{
	public static function tableName()
	{
		return 'shop_group';
	}

	public function rules()
	{
		return [
			[['name', 'code'], 'required'],
			[['name', 'code'], 'string', 'max' => 255],
			[['order'], 'integer'],
			[['order'], 'default', 'value' => 0],
		];
	}

	public function attributeLabels()
	{
		return [
			'ID' => Yii::t('shop/common', 'ID'),
			'name' => Yii::t('shop/common', 'Group name'),
			'code' => Yii::t('shop/common', 'Group code'),
			'order' => Yii::t('shop/common', 'Order'),
		];
	}

	public function getProperties()
	{
		return $this->hasMany(Property::class, ['id' => 'property_id'])->viaTable(PropertyGroups::tableName(), ['group_id' => 'id']);
	}

	public static function getGroupList()
	{
		return self::find()->select(['name', 'id'])->orderBy('order')->indexBy('id')->column();
	}
}
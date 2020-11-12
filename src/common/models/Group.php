<?php
namespace andrewdanilov\shop\common\models;

use yii\db\ActiveRecord;

/**
 * Class Group
 *
 * @package andrewdanilov\shop\common\models
 * @property string $name
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
			[['name'], 'required'],
			[['name'], 'string', 'max' => 255],
			[['order'], 'integer'],
			[['order'], 'default', 'value' => 0],
		];
	}

	public function attributeLabels()
	{
		return [
			'name' => 'Наименование группы',
			'order' => 'Порядок',
		];
	}

	public function getProperties()
	{
		return $this->hasMany(Property::class, ['id' => 'property_id'])->viaTable(PropertyGroups::tableName(), ['group_id' => 'id']);
	}

	public function getOptions()
	{
		return $this->hasMany(Property::class, ['id' => 'property_id'])->viaTable(OptionGroups::tableName(), ['group_id' => 'id']);
	}

	public static function getGroupList()
	{
		return self::find()->select(['name', 'id'])->orderBy('order')->indexBy('id')->column();
	}
}
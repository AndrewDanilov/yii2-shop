<?php
namespace andrewdanilov\shop\common\models;

use yii\db\ActiveRecord;

class PropertyGroups extends ActiveRecord
{
	public static function tableName()
	{
		return 'shop_property_groups';
	}

	public function rules()
	{
		return [
			[['group_id', 'property_id'], 'requred'],
			[['group_id', 'property_id'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'group_id' => 'Группа',
			'property_id' => 'Свойство',
		];
	}
}
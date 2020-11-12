<?php
namespace andrewdanilov\shop\common\models;

use yii\db\ActiveRecord;

class OptionGroups extends ActiveRecord
{
	public static function tableName()
	{
		return 'shop_option_groups';
	}

	public function rules()
	{
		return [
			[['group_id', 'option_id'], 'requred'],
			[['group_id', 'option_id'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'group_id' => 'Группа',
			'option_id' => 'Опция',
		];
	}
}
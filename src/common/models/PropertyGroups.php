<?php
namespace andrewdanilov\shop\common\models;

use Yii;
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
			[['group_id', 'property_id'], 'required'],
			[['group_id', 'property_id'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => Yii::t('shop/common', 'ID'),
			'group_id' => Yii::t('shop/common', 'Group'),
			'property_id' => Yii::t('shop/common', 'Property'),
		];
	}
}
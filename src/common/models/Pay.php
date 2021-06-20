<?php
namespace andrewdanilov\shop\common\models;

use Yii;

/**
 * This is the model class for table "shop_pay".
 *
 * @property int $id
 * @property int $order
 * @property string $name
 * @property string $description
 */
class Pay extends \yii\db\ActiveRecord
{

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
	        [['name'], 'required'],
	        [['order'], 'integer'],
	        [['name'], 'string', 'max' => 255],
	        [['description'], 'string'],
	        [['order'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'order' => Yii::t('shop/common', 'Order'),
            'name' => Yii::t('shop/common', 'Name'),
	        'description' => Yii::t('shop/common', 'Description'),
        ];
    }

	//////////////////////////////////////////////////////////////////

	public static function getPayList()
	{
		return self::find()->select(['name', 'id'])->indexBy('id')->column();
	}
}

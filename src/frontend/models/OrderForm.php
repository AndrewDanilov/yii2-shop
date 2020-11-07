<?php
namespace andrewdanilov\shop\frontend\models;

use Yii;
use yii\base\Model;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $postindex
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $block
 * @property string $building
 * @property string $appartment
 * @property int $delivery_id
 * @property int $pay_id
 * @property string $organization
 * @property string $inn
 */
class OrderForm extends Model
{
	public $name;
	public $email;
	public $phone;
	public $postindex;
	public $city;
	public $street;
	public $house;
	public $block;
	public $building;
	public $appartment;
	public $delivery_id;
	public $pay_id;
	public $organization;
	public $inn;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'delivery_id', 'pay_id'], 'required'],
            [['delivery_id', 'pay_id'], 'integer'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('site', 'Имя'),
			'email' => Yii::t('site', 'E-mail'),
			'phone' => Yii::t('site', 'Телефон'),
			'city' => Yii::t('site', 'Город'),
			'street' => Yii::t('site', 'Улица'),
			'house' => Yii::t('site', 'Дом'),
			'appartment' => Yii::t('site', 'Квартира'),
			'delivery_id' => Yii::t('site', 'Способ доставки'),
			'pay_id' => Yii::t('site', 'Способ оплаты'),
			'postindex' => Yii::t('site', 'Почтовый индекс'),
			'block' => Yii::t('site', 'Корпус'),
			'building' => Yii::t('site', 'Строение'),
			'organization' => Yii::t('site', 'Организация'),
			'inn' => Yii::t('site', 'ИНН'),
		];
	}
}
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
			'name' => Yii::t('shop/frontend', 'Name'),
			'email' => Yii::t('shop/frontend', 'E-mail'),
			'phone' => Yii::t('shop/frontend', 'Phone'),
			'city' => Yii::t('shop/frontend', 'City'),
			'street' => Yii::t('shop/frontend', 'Street'),
			'house' => Yii::t('shop/frontend', 'House'),
			'appartment' => Yii::t('shop/frontend', 'Apartment'),
			'delivery_id' => Yii::t('shop/frontend', 'Delivery method'),
			'pay_id' => Yii::t('shop/frontend', 'Paymetn method'),
			'postindex' => Yii::t('shop/frontend', 'Zip code'),
			'block' => Yii::t('shop/frontend', 'Block'),
			'building' => Yii::t('shop/frontend', 'Building'),
			'organization' => Yii::t('shop/frontend', 'Organization'),
			'inn' => Yii::t('shop/frontend', 'INN'),
		];
	}
}
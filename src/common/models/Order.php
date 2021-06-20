<?php
namespace andrewdanilov\shop\common\models;

use Yii;
use yii\bootstrap\Html;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use andrewdanilov\behaviors\DateBehavior;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property string $created_at
 * @property string $addressee_name
 * @property string $addressee_email
 * @property string $addressee_phone
 * @property array|string $address
 * @property int $pay_id
 * @property int $delivery_id
 * @property int $status
 * @property Product[] $products
 * @property OrderProducts[] $orderProducts
 * @property Pay $pay
 * @property Delivery $delivery
 * @property string $addresseeStr
 * @property string $addressStr
 * @property string $statusStr
 * @property string $summ
 */
class Order extends \yii\db\ActiveRecord
{
	const ORDER_STATUS_INIT = 0;
	const ORDER_STATUS_SENT = 1;
	const ORDER_STATUS_DONE = 2;
	const ORDER_STATUS_CANCELED = 10;

	/* @var array $_orderProducts */
	private $_orderProducts = []; // products for new order being created in internal format

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => DateBehavior::class,
				'dateAttributes' => [
					'created_at' => DateBehavior::DATETIME_FORMAT,
				],
			],
		];
	}

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addressee_email'], 'email'],
            [['pay_id', 'delivery_id', 'status'], 'integer'],
            [['created_at', 'address'], 'safe'],
            [['addressee_name', 'addressee_email', 'addressee_phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'created_at' => Yii::t('shop/common', 'Created at'),
            'addressee' => Yii::t('shop/common', 'Addressee'),
            'addressee_name' => Yii::t('shop/common', 'Addressee name'),
            'addressee_email' => Yii::t('shop/common', 'Addressee e-mail'),
            'addressee_phone' => Yii::t('shop/common', 'Addressee phone'),
            'address' => Yii::t('shop/common', 'Address'),
            'pay_id' => Yii::t('shop/common', 'Payment method'),
            'delivery_id' => Yii::t('shop/common', 'Delivery method'),
            'status' => Yii::t('shop/common', 'Status'),
            'summ' => Yii::t('shop/common', 'Sum'),
        ];
    }

    public function getOrderProducts()
    {
    	return $this->hasMany(OrderProducts::class, ['order_id' => 'id']);
    }

    public function getProducts()
    {
    	return $this->hasMany(Product::class, ['id' => 'product_id'])->via('orderProducts');
    }

    public function getPay()
    {
    	return $this->hasOne(Pay::class, ['id' => 'pay_id']);
    }

    public function getDelivery()
    {
    	return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }

    //////////////////////////////////////////////////////////////////

	public function afterFind()
	{
		if ($this->address) {
			$this->address = json_decode($this->address, true);
		} else {
			$this->address = [];
		}
		parent::afterFind();
	}

	public function beforeSave($insert)
	{
		if (is_array($this->address)) {
			$this->address = json_encode($this->address, JSON_UNESCAPED_UNICODE);
		}
		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		foreach ($this->_orderProducts as $_orderProduct) {
			if (!isset($_orderProduct['product_id'])) {
				$_orderProduct['product_id'] = $_orderProduct['id'];
			}
			$orderProduct = new OrderProducts();
			$orderProduct->load($_orderProduct, '');
			$orderProduct->order_id = $this->id;
			if ($orderProduct->save()) {
				if (isset($_orderProduct['product_options']) && is_array($_orderProduct['product_options'])) {
					$productOptionIds = array_keys($_orderProduct['product_options']);
					$productOptions = ProductOptions::find()->where(['id' => $productOptionIds])->all();
					foreach ($productOptions as $productOption) {
						$orderProduct->link('productOptions', $productOption);
					}
				}
			}
		}
		$this->_orderProducts = [];
		$this->address = json_decode($this->address, true);
		if (!is_array($this->address)) {
			$this->address = [];
		}
		parent::afterSave($insert, $changedAttributes);
	}

	public function beforeDelete()
	{
		foreach ($this->orderProducts as $model) {
			$model->delete();
		}
		return parent::beforeDelete();
	}

	//////////////////////////////////////////////////////////////////

	public static function getStatusList()
	{
		return [
			self::ORDER_STATUS_INIT => 'Создан',
			self::ORDER_STATUS_SENT => 'Отправлен',
			self::ORDER_STATUS_DONE => 'Завершен',
			self::ORDER_STATUS_CANCELED => 'Отменен',
		];
	}

	public function getStatusStr()
	{
		$statusList = static::getStatusList();
		if (isset($statusList[$this->status])) {
			return $statusList[$this->status];
		}
		return '';
	}

	//////////////////////////////////////////////////////////////////

	/**
	 * Returns the addressee data as a string
	 *
	 * @return string
	 */
	public function getAddresseeStr()
	{
		$content = [];
		$content[] = $this->addressee_name;
		$content[] = $this->addressee_email;
		$content[] = $this->addressee_phone;
		return implode('<br/>', $content);
	}

	/**
	 * Returns the address data as a string
	 *
	 * @return string
	 */
	public function getAddressStr()
	{
		return implode(', ', array_filter([
			ArrayHelper::getValue($this->address, 'postindex'),
			ArrayHelper::getValue($this->address, 'city'),
			ArrayHelper::getValue($this->address, 'street') ? Yii::t('shop/common', 'st.') . ' ' . ArrayHelper::getValue($this->address, 'street') : '',
			ArrayHelper::getValue($this->address, 'house') ? Yii::t('shop/common', 'house') . ' ' . ArrayHelper::getValue($this->address, 'house') : '',
			ArrayHelper::getValue($this->address, 'block') ? Yii::t('shop/common', 'block') . ' ' . ArrayHelper::getValue($this->address, 'block') : '',
			ArrayHelper::getValue($this->address, 'building') ? Yii::t('shop/common', 'building') . ' ' . ArrayHelper::getValue($this->address, 'building') : '',
			ArrayHelper::getValue($this->address, 'appartment') ? Yii::t('shop/common', 'apt.') . ' ' . ArrayHelper::getValue($this->address, 'appartment') : '',
		]));
	}

	/**
	 * The amount of goods in the order
	 *
	 * @return mixed
	 */
	public function getSumm()
	{
		return $this->getOrderProducts()->sum(new Expression('price * count'));
	}

	//////////////////////////////////////////////////////////////////

	/**
	 * Adds links to products for a new order being created
	 *
	 * @param array $orderProducts
	 */
	public function setOrderProducts($orderProducts)
	{
		$this->_orderProducts = $orderProducts;
	}
}

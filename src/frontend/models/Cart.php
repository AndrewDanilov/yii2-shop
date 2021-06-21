<?php
namespace andrewdanilov\shop\frontend\models;

use andrewdanilov\behaviors\ImagesBehavior;
use andrewdanilov\shop\common\models\Product;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Cart extends Model
{
	const SESSION_VAR_NAME = 'cartItems';

	protected $items;

	public function init()
	{
		if (!empty(Yii::$app->session[static::SESSION_VAR_NAME])) {
			$this->items = Yii::$app->session[static::SESSION_VAR_NAME];
		} else {
			$this->items = [];
			static::updateCart();
		}

		parent::init();
	}

	/**
	 * Trying to add product to cart by its ID. If product exis
	 * method returns true, overwise false.
	 *
	 * @param int $product_id
	 * @param array $options
	 * @param int $count
	 * @return bool
	 */
	public function add($product_id, $options=[], $count=1)
	{
		if ($count < 1) {
			$count = 1;
		}
		$product = Product::findOne(['id' => $product_id]);
		if ($product !== null) {
			ksort($options);
			$position_id = md5($product_id . ' ' . json_encode($options, JSON_UNESCAPED_UNICODE));
			if (array_key_exists($position_id, $this->items)) {
				$this->items[$position_id]['count'] = $this->items[$position_id]['count'] + $count;
			} else {
				$this->items[$position_id] = [
					'position_id' => $position_id,
					'product_id' => $product_id,
					'options' => $options,
					'count' => $count,
				];
			}
			static::updateCart();
			return true;
		}
		return false;
	}

	public function remove($position_id)
	{
		if (array_key_exists($position_id, $this->items)) {
			unset($this->items[$position_id]);
			static::updateCart();
		}
	}

	public function updateCount($position_id, $count)
	{
		if (array_key_exists($position_id, $this->items)) {
			if ($count > 0) {
				$this->items[$position_id]['count'] = $count;
			} else {
				unset($this->items[$position_id]);
			}
			static::updateCart();
		}
	}

	public function clear()
	{
		$this->items = [];
		static::updateCart();
	}

	public function items()
	{
		return $this->items;
	}

	public function updateCart()
	{
		Yii::$app->session->set(static::SESSION_VAR_NAME, $this->items);
	}

	/**
	 * Adds products data to cart items array by theirs ids
	 *
	 * @param Cart $cart
	 * @return array
	 */
	public static function getCartContent($cart=null)
	{
		if ($cart === null) {
			$cart = new self;
		}
		$product_ids = ArrayHelper::getColumn($cart->items, 'product_id');
		/* @var $products Product[]|ImagesBehavior[] */
		$products = Product::find()
			->select([
				Product::tableName() . '.id',
				Product::tableName() . '.brand_id',
				Product::tableName() . '.article',
				Product::tableName() . '.price',
				Product::tableName() . '.discount',
				Product::tableName() . '.name',
				Product::tableName() . '.description',
				Product::tableName() . '.slug',
				Product::tableName() . '.is_stock',
			])
			->where(['id' => $product_ids])
			->indexBy('id')
			->all();
		$totalCount = 0;
		$totalSum = 0;
		$items = [];
		foreach ($cart->items as $item) {
			if (isset($products[$item['product_id']])) {
				$product = $products[$item['product_id']];
				$totalPrice = $product->price * $item['count'];
				$totalSum += $totalPrice;
				$totalCount += $item['count'];
				$item['product'] = ArrayHelper::toArray($product);
				$item['product']['category_ids'] = $product->category_ids;
				$item['product']['sticker_ids'] = $product->sticker_ids;
				$item['product']['images'] = $product->images;
				$item['product']['price'] = $product->price;
				$item['product']['priceFormated'] = number_format($product->price, 0, '.', ' ');
				$item['product']['totalPrice'] = $totalPrice;
				$item['product']['totalPriceFormated'] = number_format($totalPrice, 0, '.', ' ');
				$items[$item['position_id']] = $item;
			}
		}
		return [
			'items' => $items,
			'totalCount' => $totalCount,
			'totalSum' => $totalSum,
			'totalSumFormated' => number_format($totalSum, 0, '.', ' '),
		];
	}
}
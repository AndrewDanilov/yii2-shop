<?php
namespace andrewdanilov\frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use andrewdanilov\shop\common\models\Delivery;
use andrewdanilov\shop\common\models\Order;
use andrewdanilov\shop\common\models\Pay;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\common\models\Option;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\frontend\models\OrderForm;

class ShopController extends Controller
{
	/**
	 * Index action.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$categories = Category::find()->orderBy('order')->all();
		return $this->render('index', ['categories' => $categories]);
	}

	/**
	 * Catalog action.
	 *
	 * @param null $categoryId
	 * @param null $brandId
	 * @param int $offset
	 * @param string $query
	 * @return mixed
	 */
	public function actionCatalog($categoryId=null, $brandId=null, $offset=0, $query='')
	{
		$filterSort = Yii::$app->request->get('filterSort');
		if (!$filterSort) {
			$filterSort = Yii::$app->session->get('filterSort');
		}
		if (!in_array($filterSort, ['popular', 'price_asc', 'price_desc'])) {
			$filterSort = 'popular';
		}
		Yii::$app->session->set('filterSort', $filterSort);

		if ($categoryId) {
			$category = Category::find()->where(['id' => $categoryId])->one();
		} else {
			$category = null;
		}
		if ($brandId) {
			$brand = Brand::find()->where(['id' => $brandId])->one();
		} else {
			$brand = null;
		}

		return $this->render('catalog', [
			'categoryId' => $categoryId,
			'brandId' => $brandId,
			'category' => $category,
			'brand' => $brand,
			'filterSort' => $filterSort,
			'query' => $query,
		]);
	}

	/**
	 * Product action.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function actionProduct($id)
	{
		$product = Product::find()->where(['id' => $id])->one();
		$options = Option::find()->orderBy('order')->indexBy('id')->all();
		$productOptions = [];
		foreach ($product->productOptions as $productOption) {
			if (!isset($productOptions[$productOption->option_id])) {
				$productOptions[$productOption->option_id] = [
					'name' => $options[$productOption->option_id]->name,
				];
			}
			$productOptions[$productOption->option_id]['items'][] = [
				'id' => $productOption->id,
				'add_price' => $productOption->add_price,
				'value' => $productOption->value,
			];
		}
		return $this->render('product', [
			'product' => $product,
			'productOptions' => $productOptions,
		]);
	}

	/**
	 * Brand action.
	 *
	 * @param int $id
	 * @return mixed
	 */
	public function actionBrand($id)
	{
		$brand = Brand::findOne(['id' => $id]);
		if (!$brand) {
			throw new BadRequestHttpException("Error request");
		}
		return $this->render('brand', ['brand' => $brand]);
	}

	/**
	 * Brands action.
	 *
	 * @return mixed
	 */
	public function actionBrands()
	{
		$brands = Brand::find()->orderBy('order')->all();
		return $this->render('brands', ['brands' => $brands]);
	}

	//////////////////////////////////////////////////////////////////

	/**
	 * Order action.
	 *
	 * @return mixed
	 */
	public function actionOrder()
	{
		$orderItems = Yii::$app->request->post('orderItems');

		$deliveries = Delivery::find()->orderBy('order')->all();
		$pays = Pay::find()->orderBy('order')->all();

		$orderForm = new OrderForm();

		if ($orderForm->load(Yii::$app->request->post(), '') && $orderForm->validate()) {
			$order = new Order();
			$order->created_at = date('d.m.Y H:i:s');
			$order->addressee_name = $orderForm->name;
			$order->addressee_email = $orderForm->email;
			$order->addressee_phone = $orderForm->phone;
			$order->address = [
				'postindex' => $orderForm->postindex,
				'city' => $orderForm->city,
				'street' => $orderForm->street,
				'house' => $orderForm->house,
				'block' => $orderForm->block,
				'building' => $orderForm->building,
				'appartment' => $orderForm->appartment,
			];
			$order->pay_id = $orderForm->pay_id;
			$order->delivery_id = $orderForm->delivery_id;
			$order->setOrderProducts($orderItems);
			if ($order->save()) {
				return $this->redirect(['site/order', 'order_id' => $order->id]);
			} else {
				$orderForm->addErrors($order->errors);
			}
		}

		return $this->render('order', [
			'orderItems' => $orderItems,
			'pays' => $pays,
			'deliveries' => $deliveries,
			'orderForm' => $orderForm,
		]);
	}

}
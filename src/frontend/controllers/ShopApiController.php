<?php
namespace andrewdanilov\shop\frontend\controllers;

use andrewdanilov\shop\frontend\models\Cart;
use andrewdanilov\shop\frontend\Module;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class ShopApiController extends Controller
{
	public function actions()
	{
		if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
			throw new BadRequestHttpException();
		}
		$this->enableCsrfValidation = false;
		Yii::$app->response->format = Response::FORMAT_JSON;
		return parent::actions();
	}

	public function actionCartAdd()
	{
		$product_id = Yii::$app->request->post('product_id');
		$cart = new Cart();
		if ($cart->add($product_id)) {
			return [
				'success' => 1,
				'cart' => Cart::getCartContent($cart),
			];
		}
		return [
			'error' => Yii::t('shop/frontend', 'Can\'t add this product to cart'),
		];
	}

	public function actionCartRemove()
	{
		$position_id = Yii::$app->request->post('position_id');
		$cart = new Cart();
		$cart->remove($position_id);
		return [
			'success' => 1,
			'cart' => Cart::getCartContent($cart),
		];
	}

	public function actionCartUpdateCount()
	{
		$position_id = Yii::$app->request->post('position_id');
		$count = Yii::$app->request->post('count');
		$cart = new Cart();
		$cart->updateCount($position_id, $count);
		return [
			'success' => 1,
			'cart' => Cart::getCartContent($cart),
		];
	}

	public function actionCartClear()
	{
		$cart = new Cart();
		$cart->clear();
		return [
			'success' => 1,
		];
	}

	public function actionSendOrder()
	{
		$cart = new Cart();
		$cartContent = Cart::getCartContent($cart);
		$clientData = [
			'client_name' => Yii::$app->request->post('client_name'),
			'client_phone' => Yii::$app->request->post('client_phone'),
			'client_address' => Yii::$app->request->post('client_address'),
		];

		$mailer = Yii::$app->mailer;
		$mailer->viewPath = Module::getInstance()->mailTemplatesPath;
		$message = $mailer->compose('send-order', ['cartContent' => $cartContent, 'clientData' => $clientData])
			->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
			->setTo(Yii::$app->params['adminEmail'])
			->setSubject(Yii::t('shop/frontend', 'Order from site'));
		// отправляем письмо
		if ($message->send()) {
			$cart->clear();
			return ['success' => 1];
		}
		return [];
	}
}
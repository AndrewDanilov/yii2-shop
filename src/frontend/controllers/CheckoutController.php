<?php
namespace andrewdanilov\shop\frontend\controllers;

use andrewdanilov\shop\frontend\Module;
use yii\web\Controller;

class CheckoutController extends Controller
{
	public function actionCart()
	{
		return $this->render('cart');
	}

	public function actionSendOrderSuccess()
	{
		return $this->render('send-order-success');
	}

	public function actionSendOrderError()
	{
		return $this->render('send-order-error');
	}
}
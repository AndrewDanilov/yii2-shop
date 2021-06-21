<?php
namespace andrewdanilov\shop\frontend\widgets\Checkout;

use andrewdanilov\shop\frontend\models\Cart;
use yii\base\Widget;

class FullCart extends Widget
{
	public function run()
	{
		return $this->render('full-cart', ['cartContent' => Cart::getCartContent()]);
	}
}
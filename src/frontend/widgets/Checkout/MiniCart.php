<?php
namespace andrewdanilov\shop\frontend\widgets\Checkout;

use yii\base\Widget;

class MiniCart extends Widget
{
	public $cartUrl;

	public function run()
	{
		if (empty($this->cartUrl)) {
			$this->cartUrl = ['/shop/checkout/cart'];
		}
		return $this->render('mini-cart', ['cartUrl' => $this->cartUrl]);
	}
}
<?php
namespace andrewdanilov\shop\frontend\widgets\Forms;

use yii\base\Widget;

class Modals extends Widget
{
	public $cartUrl;

	public function run()
	{
		if (empty($this->cartUrl)) {
			$this->cartUrl = ['/shop/checkout/cart'];
		}
		return $this->render('modals', ['cartUrl' => $this->cartUrl]);
	}
}
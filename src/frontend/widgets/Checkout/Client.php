<?php
namespace andrewdanilov\shop\frontend\widgets\Checkout;

use yii\base\Widget;

class Client extends Widget
{
	public function run()
	{
		return $this->render('client');
	}
}
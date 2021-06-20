<?php
namespace andrewdanilov\shop\backend\widgets\ProductOptions;

/**
 * Initializes the scripts necessary for the options to work
 */

use yii\base\Widget;

class ProductOptionsInit extends Widget
{
	public function run()
	{
		$view = $this->getView();
		ProductOptionsAsset::register($view);
	}
}
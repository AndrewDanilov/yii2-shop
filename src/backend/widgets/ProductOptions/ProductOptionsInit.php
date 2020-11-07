<?php
namespace andrewdanilov\shop\backend\widgets\ProductOptions;

/**
 * Инициализирует необходимые для работы опций скрипты
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
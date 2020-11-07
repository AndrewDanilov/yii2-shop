<?php
namespace andrewdanilov\shop\frontend;

class Module extends \andrewdanilov\shop\Module
{
	public function init()
	{
		// controller namespace if not defined directly
		if (empty($this->controllerNamespace) && empty($this->controllerMap)) {
			$this->controllerNamespace = 'andrewdanilov\shop\frontend\controllers';
		}
		parent::init();
	}
}
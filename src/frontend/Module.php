<?php
namespace andrewdanilov\shop\frontend;

class Module extends \andrewdanilov\shop\Module
{
	public $templatesPath;

	public function init()
	{
		// controller namespace if not defined directly
		if (empty($this->controllerNamespace) && empty($this->controllerMap)) {
			$this->controllerNamespace = 'andrewdanilov\shop\frontend\controllers';
		}

		// path to templates
		if (empty($this->templatesPath)) {
			$this->templatesPath = '@andrewdanilov/shop/frontend/views';
		}
		$this->viewPath = $this->templatesPath;

		parent::init();
	}
}
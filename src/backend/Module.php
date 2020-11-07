<?php
namespace andrewdanilov\shop\backend;

class Module extends \andrewdanilov\shop\Module
{
	public $access = ['@'];
	public $fileManager;

	public function init()
	{
		// controller namespace if not defined directly
		if (empty($this->controllerNamespace) && empty($this->controllerMap)) {
			$this->controllerNamespace = 'andrewdanilov\shop\backend\controllers';
		}
		// file manager
		if (empty($this->fileManager)) {
			$this->fileManager = [
				'basePath' => '@frontend/web',
				'paths' => [
					[
						'name' => 'Product images',
						'path' => 'upload/images/product',
					],
					[
						'name' => 'Category images',
						'path' => 'upload/images/category',
					],
					[
						'name' => 'Brand images',
						'path' => 'upload/images/brand',
					],
				],
			];
		}
		$elfinderRoots = [];
		foreach ($this->fileManager['paths'] as $path) {
			$elfinderRoots[] = [
				'baseUrl' => '',
				'basePath' => $this->fileManager['basePath'],
				'path' => $path['path'],
				'name' => $path['name'],
			];
		}
		$this->controllerMap['elfinder'] = [
			'class' => 'mihaildev\elfinder\Controller',
			'access' => $this->access,
			'roots' => $elfinderRoots,
		];
		parent::init();
	}
}
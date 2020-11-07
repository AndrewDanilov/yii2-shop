<?php
namespace andrewdanilov\shop\backend\widgets\ProductOptions;

use yii\web\AssetBundle;

class ProductOptionsAsset extends AssetBundle
{
	public $sourcePath = '@andrewdanilov/shop/backend/widgets/ProductOptions/web';
	public $css = [
	];
	public $js = [
		'js/product-options.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\jui\JuiAsset',
	];
}
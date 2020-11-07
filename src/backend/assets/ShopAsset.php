<?php
namespace andrewdanilov\shop\backend\assets;

use yii\web\AssetBundle;

class ShopAsset extends AssetBundle
{
	public $sourcePath = '@andrewdanilov/shop/backend/web';
	public $css = [
		'css/shop.css',
	];
	public $js = [
		'js/shop.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\grid\GridViewAsset',
		'andrewdanilov\gridtools\GridToolsAsset',
	];
}
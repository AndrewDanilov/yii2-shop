<?php

/* @var $cartUrl string|array */

use andrewdanilov\shop\frontend\assets\CartAsset;
use yii\helpers\Url;

CartAsset::register($this);

$assetManager = $this->getAssetManager();
$bundle = $assetManager->getBundle(CartAsset::class);
$cartImageUrl = $assetManager->getAssetUrl($bundle, 'images/cart-64.png');
?>
<a href="<?= Url::to($cartUrl) ?>" class="mini-cart">
	<img class="icon" src="<?= $cartImageUrl ?>" alt=""/>
	<span class="indicator">0</span>
</a>
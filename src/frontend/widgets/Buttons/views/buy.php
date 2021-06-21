<?php

/* @var $this \yii\web\View */
/* @var $product_id integer */
/* @var $label string */
/* @var $tag string */
/* @var $classes string */

use andrewdanilov\shop\frontend\assets\CartAsset;

CartAsset::register($this);
?>
<<?= $tag ?> href="javascript:;" data-product-id="<?= $product_id ?>" class="btn-add-to-cart <?= $classes ?>"><?= $label ?></<?= $tag ?>>

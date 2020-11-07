<?php

use andrewdanilov\shop\common\models\Product;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = 'Новый товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-product-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

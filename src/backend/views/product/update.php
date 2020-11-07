<?php

use andrewdanilov\shop\common\models\Product;

/* @var $this yii\web\View */
/* @var $model Product */

$this->title = 'Изменить товар: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shop Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

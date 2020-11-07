<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Delivery */

$this->title = 'Новый способ доставки';
$this->params['breadcrumbs'][] = ['label' => 'Способы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

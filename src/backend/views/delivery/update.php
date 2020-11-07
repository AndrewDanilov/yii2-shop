<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Delivery */

$this->title = 'Изменить способ доставки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Способы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

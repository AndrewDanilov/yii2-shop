<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Order */

$this->title = 'Изменить заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Заказ №' . $model->id;
?>
<div class="shop-order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

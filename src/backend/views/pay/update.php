<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Pay */

$this->title = 'Изменить способ оплаты: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Способы оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="pay-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

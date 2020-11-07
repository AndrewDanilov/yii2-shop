<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Pay */

$this->title = 'Новый способ оплаты';
$this->params['breadcrumbs'][] = ['label' => 'Способы оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

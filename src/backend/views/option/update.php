<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Option */

$this->title = 'Изменить опцию: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Опции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-option-update">

<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

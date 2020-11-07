<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Relation */

$this->title = 'Изменить связь: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Связи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-relation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

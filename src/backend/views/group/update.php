<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Group */

$this->title = 'Изменить группу свойств: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы свойств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

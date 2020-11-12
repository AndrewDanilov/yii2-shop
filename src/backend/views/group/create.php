<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Group */

$this->title = 'Новая группа свойств';
$this->params['breadcrumbs'][] = ['label' => 'Группы свойств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

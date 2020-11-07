<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Option */

$this->title = 'Новая опция';
$this->params['breadcrumbs'][] = ['label' => 'Опции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-option-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

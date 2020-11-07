<?php

use andrewdanilov\shop\common\models\Category;

/* @var $this yii\web\View */
/* @var $model Category */

$this->title = 'Изменить категорию: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

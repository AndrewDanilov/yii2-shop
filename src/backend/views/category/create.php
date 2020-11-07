<?php

use andrewdanilov\shop\common\models\Category;

/* @var $this yii\web\View */
/* @var $model Category */

$this->title = 'Новая категория';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

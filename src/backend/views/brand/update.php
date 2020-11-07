<?php

use andrewdanilov\shop\common\models\Brand;

/* @var $this yii\web\View */
/* @var $model Brand */

$this->title = 'Изменить бренд: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

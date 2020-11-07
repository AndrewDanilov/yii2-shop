<?php

use andrewdanilov\shop\common\models\Brand;

/* @var $this yii\web\View */
/* @var $model Brand */

$this->title = 'Новый бренд';
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

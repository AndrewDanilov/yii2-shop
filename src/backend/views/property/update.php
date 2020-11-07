<?php

use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\shop\common\models\Property;

/* @var $this yii\web\View */
/* @var $model Property|TagBehavior */

$this->title = 'Изменить свойство: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Свойства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="shop-attribute-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

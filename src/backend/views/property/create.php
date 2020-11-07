<?php

use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\shop\common\models\Property;


/* @var $this yii\web\View */
/* @var $model Property|TagBehavior */

$this->title = 'Новое свойство';
$this->params['breadcrumbs'][] = ['label' => 'Свойства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\shop\common\models\Property;


/* @var $this yii\web\View */
/* @var $model Property|TagBehavior */

$this->title = Yii::t('shop/backend', 'New property');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop/backend', 'Properties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

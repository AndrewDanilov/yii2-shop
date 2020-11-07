<?php

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Relation */

$this->title = 'Новая связь';
$this->params['breadcrumbs'][] = ['label' => 'Связи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-relation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

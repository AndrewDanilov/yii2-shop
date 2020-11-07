<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Relation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-relation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

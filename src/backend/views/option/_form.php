<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use andrewdanilov\shop\common\models\Category;

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-option-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'tagIds')->checkboxList(Category::getCategoriesList()) ?>

	<?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

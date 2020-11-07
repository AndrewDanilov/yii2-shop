<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use andrewdanilov\InputImages\InputImages;

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-brand-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'image')->widget(InputImages::class) ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'is_favorite')->checkbox() ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use andrewdanilov\ckeditor\CKEditor;
use andrewdanilov\helpers\CKEditorHelper;

/* @var $this yii\web\View */
/* @var $model andrewdanilov\shop\common\models\Pay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pay-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'description')->widget(CKEditor::class, [
		'editorOptions' => CKEditorHelper::defaultOptions(),
	]) ?>

	<?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

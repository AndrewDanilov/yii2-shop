<?php

use andrewdanilov\shop\common\models\Sticker;
use andrewdanilov\InputImages\InputImages;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model Sticker */

if ($model->isNewRecord) {
	$this->title = 'Новый стикер';
	$this->params['breadcrumbs'][] = ['label' => 'Стикеры', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
} else {
	$this->title = 'Изменить стикер: ' . $model->label;
	$this->params['breadcrumbs'][] = ['label' => 'Стикеры', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $model->label;
}
?>
<div class="item-update">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'image')->widget(InputImages::class) ?>

	<?= $form->field($model, 'order')->textInput() ?>

	<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

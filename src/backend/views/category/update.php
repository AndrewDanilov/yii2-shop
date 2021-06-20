<?php

use andrewdanilov\ckeditor\CKEditor;
use andrewdanilov\helpers\CKEditorHelper;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\InputImages\InputImages;
use andrewdanilov\shop\common\models\Category;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Category */

if ($model->isNewRecord) {
	$this->title = 'Новая категория';
	$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
} else {
	$this->title = 'Изменить категорию: ' . $model->name;
	$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $model->name;
}
?>
<div class="shop-category-update">

	<div class="shop-category-form">

		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'parent_id')->dropDownList(NestedCategoryHelper::getDropdownTree(Category::find()), ['prompt' => '']) ?>

		<?= $form->field($model, 'image')->widget(InputImages::class) ?>

		<?= $form->field($model, 'short_description')->widget(CKEditor::class, [
			'editorOptions' => CKEditorHelper::defaultOptions(200),
		]) ?>

		<?= $form->field($model, 'description')->widget(CKEditor::class, [
			'editorOptions' => CKEditorHelper::defaultOptions(400),
		]) ?>

		<?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

		<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'order')->textInput() ?>

		<div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>

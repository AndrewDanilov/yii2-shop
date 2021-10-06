<?php

use andrewdanilov\behaviors\ImagesBehavior;
use andrewdanilov\behaviors\LinkedProductsBehavior;
use andrewdanilov\behaviors\ShopOptionBehavior;
use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\ckeditor\CKEditor;
use andrewdanilov\shop\backend\Module;
use andrewdanilov\shop\common\models\Sticker;
use andrewdanilov\helpers\CKEditorHelper;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\InputImages\InputImages;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionHtml;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionsInit;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Option;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\ProductOptions;
use andrewdanilov\shop\common\models\ProductProperties;
use andrewdanilov\shop\common\models\Property;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Product|ImagesBehavior|ShopOptionBehavior|LinkedProductsBehavior */

if ($model->isNewRecord) {
	$this->title = 'Новый товар';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('shop/backend', 'Products'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
} else {
	$this->title = 'Изменить товар: ' . $model->name;
	$this->params['breadcrumbs'][] = ['label' => Yii::t('shop/backend', 'Products'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $model->name;
}
?>
<div class="shop-product-update">

	<div class="shop-product-form">

		<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?php if (Module::getInstance()->enableArticleNumbers) { ?>
			<?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>
		<?php } ?>

		<?= $form->field($model, 'is_stock')->checkbox() ?>

		<?= $form->field($model, 'images')->widget(InputImages::class, ['multiple' => true]) ?>

		<?= $form->field($model, 'description')->widget(CKEditor::class, [
			'editorOptions' => CKEditorHelper::defaultOptions(),
		]); ?>

		<?php if (Module::getInstance()->enableBrands) { ?>
			<?= $form->field($model, 'brand_id')->dropDownList(Brand::getBrandsList(), ['prompt' => '']) ?>
		<?php } ?>

		<?= $form->field($model, 'category_ids')->checkboxList(NestedCategoryHelper::getDropdownTree(Category::find()), ['class' => 'form-scroll-group']) ?>

		<?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

		<?php if (Module::getInstance()->enableDiscounts) { ?>
			<?= $form->field($model, 'discount')->textInput(['type' => 'number']) ?>
		<?php } ?>

		<?php if (Module::getInstance()->enableStickers) { ?>
			<?= $form->field($model, 'sticker_ids')->checkboxList(Sticker::getStickersList()) ?>
		<?php } ?>

		<?php /* @var ShopOptionBehavior $options */ ?>
		<?php $options = $model->getBehavior('properties') ?>
		<?php foreach ($options->initOptions() as $option) { ?>
			<?php /* @var Property $shopOption */ ?>
			<?php $shopOption = $option['option'] ?>
			<?php /* @var ProductProperties[]|ValueTypeBehavior[] $shopProductOptions */ ?>
			<?php $shopProductOptions = $option['items'] ?>
			<div class="form-group">
				<?php foreach ($shopProductOptions as $n => $shopProductOption) { ?>
					<?= $shopProductOption->formField($form, '[' . $shopOption->id . '][' . $n . ']value', $shopOption->name) ?>
				<?php } ?>
			</div>
		<?php } ?>

		<?= ProductOptionsInit::widget() ?>
		<?php /* @var ShopOptionBehavior $options */ ?>
		<?php $options = $model->getBehavior('options') ?>
		<?php foreach ($options->initOptions() as $option) { ?>
			<?php /* @var Option $shopOption */ ?>
			<?php $shopOption = $option['option'] ?>
			<?php /* @var ProductOptions[] $shopProductOptions */ ?>
			<?php $shopProductOptions = $option['items'] ?>
			<div class="form-group" data-group-option-id="<?= $shopOption->id ?>">
				<label class="control-label"><?= $shopOption->name ?></label>
				<div class="option-groups">
					<?php foreach ($shopProductOptions as $n => $shopProductOption) { ?>
						<?= ProductOptionHtml::widget([
							'optionId' => $shopOption->id,
							'productOptionsModel' => $shopProductOption,
							'order' => $n,
						]) ?>
					<?php } ?>
				</div>
				<div class="option-group-add btn btn-info"><?= Yii::t('shop/backend', 'Add option') ?></div>
			</div>
		<?php } ?>

		<?php foreach ($model->initLinkedProducts() as $link_id => $link) { ?>
			<?= $form->field($model, 'linkedProducts[' . $link_id . ']')->widget(Select2::class, [
				'data' => ArrayHelper::map(Product::find()->where(['not', ['id' => $model->id]])->all(), 'id', 'name'),
				'language' => Yii::$app->language,
				'options' => [
					'placeholder' => Yii::t('shop/backend', 'Enter product name...'),
					'multiple' => true,
				],
				'pluginOptions' => [
					'allowClear' => true,
					'tags' => true,
				],
			])->label($link['name']); ?>
		<?php } ?>

		<?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'seo_description')->textarea(['rows' => 6]) ?>

		<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?>

		<div class="form-group">
			<?= Html::submitButton(Yii::t('shop/backend', 'Save'), ['class' => 'btn btn-success']) ?>
			<?= Html::submitButton(Yii::t('shop/backend', 'Save and add more'), ['class' => 'btn btn-primary', 'name' => 'add_more', 'value' => 1]) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>

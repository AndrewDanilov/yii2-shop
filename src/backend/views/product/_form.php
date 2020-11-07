<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\behaviors\LinkedProductsBehavior;
use andrewdanilov\behaviors\ShopOptionBehavior;
use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\InputImages\InputImages;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\common\models\ProductProperties;
use andrewdanilov\shop\common\models\ProductOptions;
use andrewdanilov\shop\common\models\Property;
use andrewdanilov\shop\common\models\Option;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionsInit;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionHtml;

/* @var $this yii\web\View */
/* @var $model Product|ShopOptionBehavior|LinkedProductsBehavior */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="shop-product-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'images')->widget(InputImages::class) ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'brand_id')->dropDownList(Brand::getBrandsList()) ?>

	<?= $form->field($model, 'tagIds')->checkboxList(NestedCategoryHelper::getDropdownTree(Category::find()), ['class' => 'form-scroll-group']) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'is_new')->checkbox() ?>

    <?= $form->field($model, 'is_popular')->checkbox() ?>

    <?= $form->field($model, 'is_action')->checkbox() ?>

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
			<div class="option-group-add btn btn-info">Добавить опцию</div>
		</div>
	<?php } ?>

	<?php foreach ($model->initLinkedProducts() as $link_id => $link) { ?>
		<?= $form->field($model, 'linkedProducts[' . $link_id . ']')->widget(Select2::class, [
			'data' => ArrayHelper::map(Product::find()->where(['not', ['id' => $model->id]])->all(), 'id', 'name'),
			'language' => Yii::$app->language,
			'options' => [
				'placeholder' => Yii::t('site', 'Введите название товара...'),
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

	<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

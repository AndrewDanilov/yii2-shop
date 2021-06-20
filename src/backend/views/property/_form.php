<?php

use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\common\models\Group;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\shop\common\models\Property;
use andrewdanilov\shop\common\models\Category;

/* @var $this yii\web\View */
/* @var $model Property|TagBehavior|ValueTypeBehavior */
/* @var $form yii\widgets\ActiveForm */

ShopAsset::register($this);
?>

<div class="shop-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'type')->dropDownList(ValueTypeBehavior::getTypeList()) ?>

	<?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'is_filtered')->checkbox() ?>

	<?= $form->field($model, 'filter_type')->dropDownList(Property::getFilterTypes()) ?>

	<?= $form->field($model, 'category_ids')->checkboxList(NestedCategoryHelper::getDropdownTree(Category::find()), ['class' => 'form-scroll-group']) ?>

	<?php if (!empty($groupList = Group::getGroupList())) { ?>
		<?= $form->field($model, 'group_ids')->checkboxList($groupList) ?>
	<?php } ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('shop/backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

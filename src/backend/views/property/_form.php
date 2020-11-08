<?php

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
?>

<div class="shop-attribute-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'type')->dropDownList(ValueTypeBehavior::getTypeList()) ?>

    <?= $form->field($model, 'is_filtered')->checkbox(['Нет', 'Да']) ?>

	<?= $form->field($model, 'tagIds')->checkboxList(NestedCategoryHelper::getDropdownTree(Category::find()), ['class' => 'form-scroll-group']) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

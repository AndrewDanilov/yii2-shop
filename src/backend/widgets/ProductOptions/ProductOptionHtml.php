<?php
namespace andrewdanilov\shop\backend\widgets\ProductOptions;

/**
 * Returns the html code for linking the option with the product, containing
 * fields for entering the values assigned to the link option-product.
 */

use andrewdanilov\shop\common\models\ProductOptions;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class ProductOptionHtml extends Widget
{
	public $optionId;
	/* @var ProductOptions $productOptionsModel */
	public $productOptionsModel = null;
	public $order = null; // номер п/п, используемый для группировки значений и переводов опции в один массив

	public function run()
	{
		if ($this->productOptionsModel === null) {
			$this->productOptionsModel = new ProductOptions;
		}

		if ($this->order === null) {
			$this->order = substr(ceil(microtime(true) * 100), -6);
		}

		$option_fields = [];

		ob_start(); // буферизуем вывод, чтобы не печатался html код формы
		$form = ActiveForm::begin();
		$option_fields[] = $form->field($this->productOptionsModel, '[' . $this->optionId . '][' . $this->order . ']value')->textInput();
		$option_fields[] = $form->field($this->productOptionsModel, '[' . $this->optionId . '][' . $this->order . ']add_price')->textInput();
		ActiveForm::end();
		ob_end_clean();

		$optionGroupContent = '';
		foreach ($option_fields as $option_field) {
			$optionGroupContent .= $option_field;
		}
		$optionGroupContent .= Html::tag('div', Yii::t('shop/backend', 'Remove option'), ['class' => 'option-group-remove btn btn-danger']);

		return Html::tag('div', $optionGroupContent, ['class' => 'option-group']);
	}
}
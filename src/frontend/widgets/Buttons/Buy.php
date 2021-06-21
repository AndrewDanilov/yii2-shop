<?php
namespace andrewdanilov\shop\frontend\widgets\Buttons;

use yii\base\Widget;

class Buy extends Widget
{
	public $product_id;
	public $label = 'Buy';
	public $tag = 'a';
	public $classes = '';
	public $template;

	public function run()
	{
		if ($this->template === null) {
			$this->template = 'buy';
		}
		return $this->render($this->template, [
			'product_id' => $this->product_id,
			'label' => $this->label,
			'tag' => $this->tag,
			'classes' => $this->classes,
		]);
	}
}
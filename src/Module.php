<?php
namespace andrewdanilov\shop;

use Yii;

class Module extends \yii\base\Module
{
	public $translatesPath;

	public function init()
	{
		// path to translates
		if (empty($this->translatesPath)) {
			$this->translatesPath = '@andrewdanilov/shop/common/messages';
		}
		// I18N
		$this->registerTranslations();
		parent::init();
	}

	public function registerTranslations()
	{
		Yii::$app->i18n->translations['shop/*'] = [
			'class'          => 'yii\i18n\PhpMessageSource',
			'sourceLanguage' => 'en-US',
			'basePath'       => $this->translatesPath,
		];
	}
}
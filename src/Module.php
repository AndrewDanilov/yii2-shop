<?php
namespace andrewdanilov\shop;

use Yii;

class Module extends \yii\base\Module
{
	public $mailTemplatesPath;
	public $translatesPath;
	public $currency = 'USD';

	public function init()
	{
		// path to mail templates
		if (empty($this->mailTemplatesPath)) {
			$this->mailTemplatesPath = '@andrewdanilov/shop/common/mail';
		}

		// path to translates
		if (empty($this->translatesPath)) {
			$this->translatesPath = '@andrewdanilov/shop/common/messages';
		}
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
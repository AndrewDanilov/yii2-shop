<?php
namespace andrewdanilov\shop;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
	public $appFrontendId = 'app-frontend';

	/**
	 * @inheritdoc
	 */
	public function bootstrap($app)
	{
		if (Yii::$app->id === $this->appFrontendId) {
			$app->getUrlManager()->addRules([
				'shop-api/<action>' => 'shop/shop-api/<action>',
				'checkout/<action>' => 'shop/checkout/<action>',
				[
					'class' => 'andrewdanilov\shop\frontend\components\UrlRule',
				]
			], false);
		}
	}
}
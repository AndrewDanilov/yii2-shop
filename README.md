Yii2 Shop
===================


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require andrewdanilov/yii2-shop "~1.0.0"
```

or add

```
"andrewdanilov/yii2-shop": "~1.0.0"
```

to the `require` section of your `composer.json` file.

Then run db migrations, to create needed tables:

```
php yii migrate --migrationPath=@andrewdanilov/shop/console/migrations
```

Do not forget to run migrations after extension updates too.

Usage
-----

_Extension is in testing. Do not use it!_

In backend main config `modules` section add:

```php
$config = [
	// ...
	'modules' => [
		// ...
		'shop' => [
			'class' => 'andrewdanilov\shop\backend\Module',
			// access role for module controllers, optional, default is ['@']
			'access' => ['admin'],
			// file manager configuration, optional, default is:
			'fileManager' => [
				'basePath' => '@frontend/web',
				'paths' => [
					[
						'name' => 'Product images',
						'path' => 'upload/images/product',
					],
					[
						'name' => 'Category images',
						'path' => 'upload/images/category',
					],
					[
						'name' => 'Brand images',
						'path' => 'upload/images/brand',
					],
				],
			],
		],
	],
];
```

Here `access` option allows restricting access to defined roles.

In frontend main config `modules` section add:

```php
$config = [
	// ...
	'modules' => [
		// ...
		'shop' => [
			'class' => 'andrewdanilov\shop\frontend\Module',
		],
	],
];
```

To use extension's default url rules, add `UrlRule` class to `urlManager` `rules` section of frontend main config:

```php
$config = [
	// ...
	'components' => [
		// ...
		'urlManager' => [
			// ...
			'rules' => [
				// ...
				[
					'class' => 'andrewdanilov\shop\frontend\components\UrlRule',
				],
				// ...
			],
		],
	],
];
```

Backend menu items:

```php
$shop_menu_items = [
	['label' => 'Заказы', 'icon' => 'shopping-bag', 'url' => ['/shop/order']],
	['label' => 'Товары', 'icon' => 'shopping-cart', 'url' => ['/shop/product']],
	['label' => 'Бренды', 'icon' => 'leaf', 'url' => ['/shop/brand']],
	['label' => 'Свойства', 'icon' => 'list', 'url' => ['/shop/property']],
	['label' => 'Опции', 'icon' => 'check-square', 'url' => ['/shop/option']],
	['label' => 'Группы свойств', 'icon' => 'tags', 'url' => ['/shop/group']],
	['label' => 'Связи', 'icon' => 'link', 'url' => ['/shop/relation']],
	['label' => 'Способы оплаты', 'icon' => 'wallet', 'url' => ['/shop/pay']],
	['label' => 'Способы доставки', 'icon' => 'truck', 'url' => ['/shop/delivery']],
];
```
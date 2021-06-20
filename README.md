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
			// path to user translates, optional, default is '@andrewdanilov/shop/common/messages'
			'translatesPath' => '@common/messages/shop',
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
					[
						'name' => 'Sticker images',
						'path' => 'upload/images/sticker',
					],
					[
						'name' => 'Documents',
						'path' => 'upload/images/docs',
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
			// path to user translates, optional, default is '@andrewdanilov/shop/common/messages'
			'translatesPath' => '@common/messages/shop',
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
	['label' => 'Стикеры', 'icon' => 'bookmark', 'url' => ['/shop/sticker']],
];

echo Menu::widget(['items' => $shop_menu_items]);
```


Features
--------

### I18n

Extension supports internationalisation. You can set your language in `common/config/main.php`

```php
return [
	// ...
	'language' => 'ru-RU',
	// ...
];
```

On the moment you can use one of languages out of the box: English, Russian. Also you can create and use your own
translations by defining `translatesPath` property of shop module (see above). Therefore, you need to place
language files to `xx-XX` folder inside `translatesPath` path. You can copy example from `src/common/messages` path
of extension.
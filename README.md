Yii2 Shop
===================

Customizable shop module with hierarchical categories, product properties and options, cart, product stickers. Supports i18n.


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require andrewdanilov/yii2-shop "~1.1.0"
```

or add

```
"andrewdanilov/yii2-shop": "~1.1.0"
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

In frontend main config `modules` section and `bootstrap` section add `shop` module:

```php
$config = [
    // ...
    'modules' => [
        // ...
        'shop' => [
            'class' => 'andrewdanilov\shop\frontend\Module',
            // path to template views, optional, default is '@andrewdanilov/shop/frontend/views'
            'templatesPath' => '@frontend/views/shop',
            // path to mail template views, optional, default is '@andrewdanilov/shop/common/mail'
            'mailTemplatesPath' => '@common/mail/shop',
            // path to user translates, optional, default is '@andrewdanilov/shop/common/messages'
            'translatesPath' => '@common/messages/shop',
            // main currency, using by shop, optional, default is 'USD'
            'currency' => '$',
        ],
    ],
    // If you use own templates paths, you need to add `shop` module to `bootstrap` section
    // to enable i18n and other settings before using module parts.
    // This is optional, but recommended in any way.
    'bootstrap' => [
        // ...
        'shop',
    ],
];
```

In `common/config/params.php` config file add/modify `adminEmail`, `senderEmail` and `senderName` params like this

```php
return [
    // ...
    'adminEmail' => ['admin@example.com', 'admin2@example.com'],
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Robot',
    // ...
];
```

You will get system messages (i.e., order from site) on one of `adminEmail` e-mails from `senderEmail` e-mail.

To transport e-mail messages you must correctly set up `mailer` component in `common/config/main-local.php`.
For example SMTP transport:

```php
return [
    'components' => [
        // ...
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'username' => 'sender@example.com',
                'password' => 'example_password',
                'host' => 'smtp.example.com',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
    ],
];
```

Backend menu items:

```php
$shop_menu_items = [
    ['label' => 'Заказы', 'url' => ['/shop/order']],
    ['label' => 'Товары', 'url' => ['/shop/product']],
    ['label' => 'Бренды', 'url' => ['/shop/brand']],
    ['label' => 'Свойства', 'url' => ['/shop/property']],
    ['label' => 'Опции', 'url' => ['/shop/option']],
    ['label' => 'Группы свойств', 'url' => ['/shop/group']],
    ['label' => 'Связи', 'url' => ['/shop/relation']],
    ['label' => 'Способы оплаты', 'url' => ['/shop/pay']],
    ['label' => 'Способы доставки', 'url' => ['/shop/delivery']],
    ['label' => 'Стикеры', 'url' => ['/shop/sticker']],
];

echo \yii\widgets\Menu::widget(['items' => $shop_menu_items]);
```

Widgets
-------

You can use these widgets to add cart, mini-cart, modal windows and buy buttons to your shop:

```php
// Place this widget everywhere you want to see buy button
echo \andrewdanilov\shop\frontend\widgets\Buttons\Buy::widget([
    'product_id' => 123,
    // optional, text label displaying on buy button
    'label' => 'Buy it!',
    // optional, html tag uset for representing buy button
    'tag' => 'div',
    // optional, extended classes for buy button
    'classes' => 'orange-buy-button',
    // optional, use to define own template for buy button
    'template' => '@frontend/views/shop/buy-button',
]);

// Place these two widgets on checkout page.
// First is for displaying form to retrieve client information like e-mail, phone, etc.
// Second is for displaying cart contents table.
\andrewdanilov\shop\frontend\widgets\Checkout\Client::widget();
\andrewdanilov\shop\frontend\widgets\Checkout\FullCart::widget();

// Widget for mini-cart button. Place it somewhere in main layout.
\andrewdanilov\shop\frontend\widgets\Checkout\MiniCart::widget();

// Widget for placing modal windows on page
\andrewdanilov\shop\frontend\widgets\Forms\Modals::widget();
```

If you need your own content inside the widgets, you can copy the widget directory to your desired location and
then call them from there. If you do, remember to change the namespaces accordingly.


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

On the moment you can use one of languages out of the box: English, Russian. Also, you can create and use your own
translations by defining `translatesPath` property of shop module (see above). Therefore, you need to place
language files to `xx-XX` folder inside `translatesPath` path. You can copy example from `src/common/messages` path
of extension.
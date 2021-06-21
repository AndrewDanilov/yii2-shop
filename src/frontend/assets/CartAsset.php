<?php
namespace andrewdanilov\shop\frontend\assets;

use andrewdanilov\shop\frontend\Module;
use Yii;
use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
	public $sourcePath = '@andrewdanilov/shop/frontend/web';
    public $css = [
        'css/cart.css',
    ];
    public $js = [
    	'js/cart.js',
    ];
    public $depends = [
	    'yii\web\JqueryAsset',
	    'andrewdanilov\fancybox\FancyboxAsset',
    ];

    public function init()
    {
	    Yii::$app->view->registerJsVar('shop', [
	    	'messages' => [
	    		'errorAddToCart' => Yii::t('shop/frontend', 'Error adding to cart'),
			    'questionRemoveProductFromCart' => Yii::t('shop/frontend', 'Are you sure you want to remove an item from your cart?'),
			    'errorEmptyFormFields' => Yii::t('shop/frontend', 'You must fill all required fields'),
			    'removeBtn' => Yii::t('shop/frontend', 'Remove'),
		    ],
		    'currency' => Module::getInstance()->currency,
	    ]);
	    parent::init();
    }
}

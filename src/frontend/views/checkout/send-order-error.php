<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Оформление заказа';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Оформление заказа',
]);
?>

<div class="section">
	<div class="container">
		<h1><?= Yii::t('shop/frontend', 'Order error') ?></h1>
		<div>
			<?= Yii::t('shop/frontend', 'An unexpected error occurred while submitting your order. Please go {cart_link} and resend your order. We apologize for any inconvenience caused.', [
				'cart_link' => Html::a(Yii::t('shop/frontend', 'to cart'), ['/shop/checkout/cart']),
			]) ?>
		</div>
	</div>
</div>


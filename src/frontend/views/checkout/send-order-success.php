<?php

/* @var $this yii\web\View */

$this->title = 'Оформление заказа';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Оформление заказа',
]);
?>

<div class="section">
	<section class="container">
		<h1><?= Yii::t('shop/frontend', 'Order sent') ?></h1>
		<div>
			<?= Yii::t('shop/frontend', 'Thak you for your order! Our manager will call you back soon.') ?>
		</div>
	</section>
</div>


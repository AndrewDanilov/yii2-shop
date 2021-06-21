<?php
/* @var $this yii\web\View */

use andrewdanilov\shop\frontend\models\Cart;
use andrewdanilov\shop\frontend\widgets\Checkout\FullCart;
use andrewdanilov\shop\frontend\widgets\Checkout\Client;
use yii\helpers\Html;

$this->title = 'Оформление заказа';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Оформление заказа',
]);
?>

<div class="section">
	<div class="container">
		<h1>Оформление заказа</h1>
		<div class="checkout-form">
			<?php if (count((new Cart())->items())) { ?>
				<?= FullCart::widget() ?>
				<?= Client::widget() ?>
				<?= Html::a('Заказать', 'javascript:;', ['class' => 'btn-checkout']) ?>
			<?php } else { ?>
				В вашей корзине пока нет товаров
			<?php } ?>
		</div>
	</div>
</div>


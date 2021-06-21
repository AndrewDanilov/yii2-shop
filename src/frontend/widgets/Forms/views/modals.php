<?php

/* @var $this \yii\web\View */
/* @var $cartUrl string */

use yii\helpers\Url;

?>
<div style="display:none;">
	<div id="cart_success_modal">
		<h3><?= Yii::t('shop/frontend', 'Your order') ?></h3>
		<div class="ordered-product-content">
			<div class="ordered-product-image">
				<img src="" alt="">
			</div>
			<div class="ordered-product-info">
				<div class="ordered-product-title"></div>
				<div class="ordered-product-price"></div>
			</div>
		</div>
		<div class="ordered-product-actions">
			<a class="btn action-close" href="javascript:$.fancybox.close(true);"><?= Yii::t('shop/frontend', 'Continue shopping') ?></a>
			<a class="btn action-cart" href="<?= Url::to($cartUrl) ?>"><?= Yii::t('shop/frontend', 'Cart') ?></a>
		</div>
	</div>

	<div id="confirm_modal">
		<div class="question"></div>
		<div class="actions">
			<a class="btn action-confirm" href="javascript:;"><?= Yii::t('yii', 'Yes') ?></a>
			<a class="btn action-close" href="javascript:$.fancybox.close(true);"><?= Yii::t('shop/frontend', 'Cancel') ?></a>
		</div>
	</div>
</div>
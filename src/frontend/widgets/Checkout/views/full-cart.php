<?php

/* @var $this \yii\web\View */
/* @var $cartContent array */

use andrewdanilov\shop\frontend\assets\CartAsset;
use andrewdanilov\shop\frontend\Module;

CartAsset::register($this);
?>

<table class="cart-table">
	<thead>
		<tr>
			<th></th>
			<th><?= Yii::t('shop/frontend', 'Product') ?></th>
			<th><?= Yii::t('shop/frontend', 'Price') ?>, <?= Module::getInstance()->currency ?></th>
			<th><?= Yii::t('shop/frontend', 'Count') ?></th>
			<th><?= Yii::t('shop/frontend', 'Sum') ?>, <?= Module::getInstance()->currency ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cartContent['items'] as $item) { ?>
			<tr data-position-id="<?= $item['position_id'] ?>">
				<td class="image">
					<img height="50" src="<?= $item['product']['images'][0] ?>" alt="">
				</td>
				<td class="title">
					<?= $item['product']['name'] ?>
				</td>
				<td class="price"><?= $item['product']['priceFormated'] ?></td>
				<td class="count"><input type="number" value="<?= $item['count'] ?>"/></td>
				<td class="total-price"><?= $item['product']['totalPriceFormated'] ?></td>
				<td>
					<a href="javascript:;" class="cart-remove-position"><?= Yii::t('shop/frontend', 'Remove') ?></a>
				</td>
			</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"><?= Yii::t('shop/frontend', 'Total:') ?></td>
			<td class="total-summ"><?= $cartContent['totalSumFormated'] ?></td>
		</tr>
	</tfoot>
</table>

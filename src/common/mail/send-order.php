<?php

/* @var $this yii\web\View */
/* @var $cartContent array */
/* @var $clientData array */

use andrewdanilov\shop\frontend\Module;

?>

Имя: <?= $clientData['client_name'] ?><br>
Телефон: <?= $clientData['client_phone'] ?><br>
Адрес: <?= $clientData['client_address'] ?><br><br>

<table cellpadding="4">
	<thead>
	<tr>
		<th></th>
		<th><?= Yii::t('shop/frontend', 'Product') ?></th>
		<th><?= Yii::t('shop/frontend', 'Price') ?>, <?= Module::getInstance()->currency ?></th>
		<th><?= Yii::t('shop/frontend', 'Count') ?></th>
		<th><?= Yii::t('shop/frontend', 'Sum') ?>, <?= Module::getInstance()->currency ?></th>
	</tr>
	</thead>
	<tbody>
	<?php $total_summ = 0 ?>
	<?php foreach ($cartContent['items'] as $item) { ?>
		<tr>
			<td class="image" style="vertical-align:top;">
				<img height="50" src="https://<?= $_SERVER['HTTP_HOST'] . $item['product']['images'][0] ?>" alt="">
			</td>
			<td class="title" style="vertical-align:top;">
				<?= $item['product']['name'] ?>
			</td>
			<td class="price" style="vertical-align:top;"><?= $item['product']['priceFormated'] ?></td>
			<td class="count" style="vertical-align:top;"><?= $item['count'] ?></td>
			<td class="total-price" style="vertical-align:top;"><?= $item['product']['totalPriceFormated'] ?></td>
		</tr>
	<?php } ?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4" style="vertical-align:top;"><?= Yii::t('shop/frontend', 'Total:') ?></td>
		<td class="total-summ" style="vertical-align:top;"><?= $cartContent['totalSumFormated'] ?></td>
	</tr>
	</tfoot>
</table>

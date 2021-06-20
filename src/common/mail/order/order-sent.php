<?php

/* @var andrewdanilov\shop\common\models\Order $model */

?>

<?= Yii::t('shop/common', 'Order from site') ?> <?= Yii::$app->id ?>:<br />
<br />
<?= Yii::t('shop/common', 'Addressee name') ?>: <?= $model->addressee_name ?><br />
<?= Yii::t('shop/common', 'E-mail') ?>: <?= $model->addressee_email ?><br />
<?= Yii::t('shop/common', 'Phone') ?>: <?= $model->addressee_phone ?><br />
<?= Yii::t('shop/common', 'Address') ?>: <?= $model->addressStr ?><br />
<?= Yii::t('shop/common', 'Delivery method') ?>: <?= $model->delivery->name ?><br />
<?= Yii::t('shop/common', 'Payment method') ?>: <?= $model->pay->name ?><br />
<br />
<?= Yii::t('shop/common', 'Products') ?>:<br />
<?php foreach ($model->orderProducts as $orderProduct) { ?>
	<?= $orderProduct->name ?>,
	<?= $orderProduct->price ?>,
	<?= $orderProduct->count ?> <?= Yii::t('shop/common', 'pcs.') ?>,
	<?= $orderProduct->productOptionsStr ?>
	<br />
<?php } ?>
<?= Yii::t('shop/common', 'Sum') ?>: <?= $model->summ ?> руб.
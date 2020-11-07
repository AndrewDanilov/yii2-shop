<?php

/* @var andrewdanilov\shop\common\models\Order $model */

?>

<?= Yii::t('site', 'Заказ с сайта') ?> <?= Yii::$app->params['sitename'] ?>:<br />
<br />
<?= Yii::t('site', 'Имя') ?>: <?= $model->addressee_name ?><br />
<?= Yii::t('site', 'E-mail') ?>: <?= $model->addressee_email ?><br />
<?= Yii::t('site', 'Телефон') ?>: <?= $model->addressee_phone ?><br />
<?= Yii::t('site', 'Адрес') ?>: <?= $model->addressStr ?><br />
<?= Yii::t('site', 'Способ доставки') ?>: <?= $model->delivery->name ?><br />
<?= Yii::t('site', 'Способ оплаты') ?>: <?= $model->pay->name ?><br />
<br />
<?= Yii::t('site', 'Товары') ?>:<br />
<?php foreach ($model->orderProducts as $orderProduct) { ?>
	<?= $orderProduct->name ?>,
	<?= $orderProduct->price ?>,
	<?= $orderProduct->count ?> <?= Yii::t('site', 'шт.') ?>,
	<?= $orderProduct->productOptionsStr ?>
	<br />
<?php } ?>
<?= Yii::t('site', 'На сумму') ?>: <?= $model->summ ?> руб.
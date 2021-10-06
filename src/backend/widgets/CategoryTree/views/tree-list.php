<?php

/* @var View $this */
/* @var array $tree */
/* @var string $filteredItemsListUriAction */
/* @var string $filteredItemsListUriParamName */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

$filterValues = array_filter(Yii::$app->request->get($filteredItemsListUriParamName, []));
?>

<?php if (!empty($tree)) { ?>
	<div class="shop-tree-list">
		<?php foreach ($tree as $item) { ?>
			<div class="shop-list-item level-<?= $item['level'] ?> <?php if (ArrayHelper::getValue($filterValues, 'category_id') == $item['category']['id']) { ?>active-item<?php } ?>">
				<div class="shop-tree-actions">
					<?= Html::a('<span class="fa fa-folder"></span>', [$filteredItemsListUriAction, $filteredItemsListUriParamName => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]); ?>
					<?= Html::a('<span class="fa fa-pen"></span>', ['category/update', 'id' => $item['category']['id']], ['title' => Yii::t('shop/backend', 'Edit')]) ?>
					<?= Html::a('<span class="fa fa-trash"></span>', ['category/delete', 'id' => $item['category']['id']], ['data' => ['confirm' => Yii::t('shop/backend', 'Are you sure you want to delete this category?'), 'method' => 'post'], 'title' => Yii::t('shop/backend', 'Remove')]) ?>
				</div>
				<div class="shop-tree-link"><?= Html::a($item['category']['name'] . ' (' . $item['category']['count'] . ')', [$filteredItemsListUriAction, $filteredItemsListUriParamName => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]) ?></div>
			</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="alert alert-warning">
		<?= Yii::t('shop/backend', 'There is no categories yet. Please, add some categories by clicking button <b>New category</b>') ?>
	</div>
<?php } ?>
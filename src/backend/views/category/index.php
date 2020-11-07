<?php

use yii\helpers\Html;
use andrewdanilov\shop\backend\assets\ShopAsset;

/* @var $this yii\web\View */
/* @var $tree array */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);
?>
<div class="shop-category-index">

    <p>
        <?= Html::a('Новая категория', ['category/create'], ['class' => 'btn btn-success']) ?>
    </p>

	<div class="shop-tree-list">
		<?php foreach ($tree as $item) { ?>
			<div class="shop-list-item level-<?= $item['level'] ?>">
				<div class="shop-tree-actions">
					<?= Html::a('<span class="fa fa-pen"></span>', ['category/update', 'id' => $item['category']->id]) ?>
					<?= Html::a('<span class="fa fa-trash"></span>', ['category/delete', 'id' => $item['category']->id], ['data' => ['confirm' => 'Вы уверены, что хотите удалить этот элемент?', 'method' => 'post']]) ?>
				</div>
				<div class="shop-tree-link"><?= Html::a($item['category']->name . ' (' . $item['category']->getProducts()->count() . ')', ['product/index', 'ProductSearch' => ['category_id' => $item['category']->id]]) ?></div>
			</div>
		<?php } ?>
	</div>
</div>

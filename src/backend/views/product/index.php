<?php

use yii\helpers\Html;
use yii\grid\GridView;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\backend\assets\ShopAsset;

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);
?>

<div class="shop-product-index">

	<div class="shop-tree-list">
		<?php foreach ($tree as $item) { ?>
			<div class="shop-list-item level-<?= $item['level'] ?>">
				<div class="shop-tree-actions">
					<?= Html::a('<span class="fa fa-pen"></span>', ['product/update-category', 'id' => $item['category']->id]) ?>
					<?= Html::a('<span class="fa fa-trash"></span>', ['product/delete-category', 'id' => $item['category']->id], ['data' => ['confirm' => 'Вы уверены, что хотите удалить эту категорию?', 'method' => 'post']]) ?>
				</div>
				<div class="shop-tree-link"><?= Html::a($item['category']->name . ' (' . $item['category']->getProducts()->count() . ')', ['product/index', 'ProductSearch' => ['category_id' => $item['category']->id]]) ?></div>
			</div>
		<?php } ?>
	</div>

	<p>
		<?php
		$create_product_url = ['product/update'];
		$create_category_url = ['product/update-category'];
		if (!empty($productSearch = array_filter(Yii::$app->request->get('ProductSearch', [])))) {
			$create_product_url['ProductSearch'] = $productSearch;
			$create_category_url['ProductSearch'] = $productSearch;
		}
		?>
		<?= Html::a('Новый товар', $create_product_url, ['class' => 'btn btn-success']) ?>
		<?= Html::a('Новая категория', $create_category_url, ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			[
				'attribute' => 'id',
				'headerOptions' => ['width' => 100],
			],
			'article',
			[
				'attribute' => 'image',
				'format' => 'raw',
				'headerOptions' => ['style' => 'width:100px'],
				'value' => function($model) { return Html::img($model->image, ['width' => '100']); },
			],
			'name',
			'price',
			'discount',
			[
				'attribute' => 'brand_id',
				'value' => 'brand.name',
				'filter' => Brand::getBrandsList(),
			],
			[
				'attribute' => 'category_id',
				'value' => 'categoriesDelimitedString',
				'filter' => NestedCategoryHelper::getDropdownTree(Category::find()),
				'filterOptions' => ['style' => 'font-family:monospace;'],
			],
			[
				'attribute' => 'marks',
				'value' => 'marksDelimitedString',
				'filter' => Product::getMarksList(),
			],

			[
				'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
				'template' => '{update}{delete}',
				'contentOptions' => ['style' => 'white-space: nowrap;'],
			],
		],
	]); ?>
</div>

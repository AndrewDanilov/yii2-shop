<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel ProductSearch */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Sticker;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('shop/backend', 'Products');
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);

$create_product_url = ['product/update'];
$create_category_url = ['category/update'];
if (!empty($productSearch = array_filter(Yii::$app->request->get('ProductSearch', [])))) {
	$create_product_url['ProductSearch'] = $productSearch;
	$create_category_url['ProductSearch'] = $productSearch;
}
?>

<div class="shop-product-index">

	<div class="shop-editor-actions">
		<?= Html::a(Yii::t('shop/backend', 'New product'), $create_product_url, ['class' => 'btn btn-success']) ?>
		<?= Html::a(Yii::t('shop/backend', 'New category'), $create_category_url, ['class' => 'btn btn-success']) ?>
	</div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<div class="shop-tree-list">
				<?php foreach ($tree as $item) { ?>
					<div class="shop-list-item level-<?= $item['level'] ?> <?php if (ArrayHelper::getValue($productSearch, 'category_id') == $item['category']['id']) { ?>active-item<?php } ?>">
						<div class="shop-tree-actions">
							<?= Html::a('<span class="fa fa-folder"></span>', ['product/index', 'ProductSearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]); ?>
							<?= Html::a('<span class="fa fa-pen"></span>', ['category/update', 'id' => $item['category']['id']], ['title' => Yii::t('shop/backend', 'Edit')]) ?>
							<?= Html::a('<span class="fa fa-trash"></span>', ['category/delete', 'id' => $item['category']['id']], ['data' => ['confirm' => Yii::t('shop/backend', 'Are you sure you want to delete this category?'), 'method' => 'post'], 'title' => Yii::t('shop/backend', 'Remove')]) ?>
						</div>
						<div class="shop-tree-link"><?= Html::a($item['category']['name'] . ' (' . $item['category']['count'] . ')', ['product/index', 'ProductSearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]) ?></div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

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
				'filter' => NestedCategoryHelper::getDropdownTree(Category::find()->orderBy('order')),
				'filterOptions' => ['style' => 'font-family:monospace;'],
			],
			[
				'attribute' => 'sticker_id',
				'value' => 'stickersDelimitedString',
				'filter' => Sticker::getStickersList(),
			],
			'is_stock:boolean',
			'order',

			[
				'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
				'template' => '{update}{delete}',
				'contentOptions' => ['style' => 'white-space: nowrap;'],
			],
		],
	]); ?>
</div>

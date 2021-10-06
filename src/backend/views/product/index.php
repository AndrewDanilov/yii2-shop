<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel ProductSearch */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\gridtools\FontawesomeActionColumn;use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\backend\Module;
use andrewdanilov\shop\backend\widgets\CategoryTree\CategoryTreeFilterList;
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

$grid_colmns = [];
$grid_colmns[] = [
	'attribute' => 'id',
	'headerOptions' => ['width' => 100],
];
if (Module::getInstance()->enableArticleNumbers) {
	$grid_colmns[] = 'article';
}
$grid_colmns[] = [
	'attribute' => 'image',
	'format' => 'raw',
	'headerOptions' => ['style' => 'width:100px'],
	'value' => function($model) { return Html::img($model->image, ['width' => '100']); },
];
$grid_colmns[] = 'name';
$grid_colmns[] = 'price';
if (Module::getInstance()->enableArticleNumbers) {
	$grid_colmns[] = 'discount';
}
if (Module::getInstance()->enableBrands) {
	$grid_colmns[] = [
		'attribute' => 'brand_id',
		'value' => 'brand.name',
		'filter' => Brand::getBrandsList(),
	];
}
$grid_colmns[] = [
	'attribute' => 'category_id',
	'value' => 'categoriesDelimitedString',
	'filter' => NestedCategoryHelper::getDropdownTree(Category::find()->orderBy('order')),
	'filterOptions' => ['style' => 'font-family:monospace;'],
];
if (Module::getInstance()->enableArticleNumbers) {
	$grid_colmns[] = [
		'attribute' => 'sticker_id',
		'value' => 'stickersDelimitedString',
		'filter' => Sticker::getStickersList(),
	];
}
$grid_colmns[] = 'is_stock:boolean';
$grid_colmns[] = 'order';
$grid_colmns[] = [
	'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
	'template' => '{update}{delete}',
	'buttons' => [
		'update' => function($url, $model, $key) {
			$productSearch = Yii::$app->request->get('ProductSearch');
			$url = ['update', 'id' => $model->id];
			if (!empty($productSearch['category_id'])) {
				$url['ProductSearch']['category_id'] = $productSearch['category_id'];
			}
			if (!empty($productSearch['brand_id'])) {
				$url['ProductSearch']['brand_id'] = $productSearch['brand_id'];
			}
			$options = [
				'title' => Yii::t('yii', 'Update'),
				'aria-label' => Yii::t('yii', 'Update'),
				'data-pjax' => '0',
			];
			$icon = Html::tag('span', '', ['class' => "fa fa-pen"]);
			return Html::a($icon, $url, $options);
		}
	],
	'contentOptions' => ['style' => 'white-space: nowrap;'],
];
?>

<div class="shop-product-index">

	<div class="shop-editor-actions">
		<?= Html::a(Yii::t('shop/backend', 'New product'), $create_product_url, ['class' => 'btn btn-success']) ?>
		<?= Html::a(Yii::t('shop/backend', 'New category'), $create_category_url, ['class' => 'btn btn-primary']) ?>
	</div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<?= CategoryTreeFilterList::widget([
				'tree' => $tree,
				'filteredItemsListUriAction' => 'product/index',
				'filteredItemsListUriParamName' => 'ProductSearch',
			]) ?>
		</div>
	</div>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $grid_colmns,
	]); ?>
</div>

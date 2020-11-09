<?php

use yii\helpers\Html;
use yii\grid\GridView;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Brand;
use andrewdanilov\shop\backend\models\ProductSearch;

/* @var $this yii\web\View */
/* @var $searchModel ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-product-index">

    <p>
        <?= Html::a('Новый товар', ['create'], ['class' => 'btn btn-success']) ?>
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
		        'class' => 'yii\grid\ActionColumn',
		        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

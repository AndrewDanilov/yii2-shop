<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel OptionSearch */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\OptionSearch;
use andrewdanilov\shop\backend\widgets\CategoryTree\CategoryTreeFilterList;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Option;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('shop/backend', 'Options');
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);

$create_category_url = ['category/update'];
$create_option_url = ['option/create'];
if (!empty($optionSearch = array_filter(Yii::$app->request->get('OptionSearch', [])))) {
	$create_option_url['ProductSearch'] = $optionSearch;
}
?>
<div class="shop-option-index">

    <div class="shop-editor-actions">
        <?= Html::a(Yii::t('shop/backend', 'New option'), $create_option_url, ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('shop/backend', 'New product category'), $create_category_url, ['class' => 'btn btn-primary']) ?>
    </div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<?= CategoryTreeFilterList::widget([
				'tree' => $tree,
				'filteredItemsListUriAction' => 'option/index',
				'filteredItemsListUriParamName' => 'OptionSearch',
			]) ?>
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
	        'name',
	        [
		        'attribute' => 'category_ids',
		        'value' => function($model) {
			        /* @var $model Option */
			        return $model->categoriesDelimitedString();
		        },
		        'filter' => NestedCategoryHelper::getDropdownTree(Category::find()),
		        'filterOptions' => ['style' => 'font-family:monospace;'],
	        ],
	        'is_filtered:boolean',
            'order',

	        [
		        'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
		        'template' => '{update}{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

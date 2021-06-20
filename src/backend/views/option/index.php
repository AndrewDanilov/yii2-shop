<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel OptionSearch */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\OptionSearch;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Option;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('shop/backend', 'Options');
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);

$create_option_url = ['option/create'];
if (!empty($optionSearch = array_filter(Yii::$app->request->get('OptionSearch', [])))) {
	$create_option_url['ProductSearch'] = $optionSearch;
}
?>
<div class="shop-option-index">

    <div class="shop-editor-actions">
        <?= Html::a(Yii::t('shop/backend', 'New option'), $create_option_url, ['class' => 'btn btn-success']) ?>
    </div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<div class="shop-tree-list">
				<?php foreach ($tree as $item) { ?>
					<div class="shop-list-item level-<?= $item['level'] ?> <?php if (ArrayHelper::getValue($optionSearch, 'category_id') == $item['category']['id']) { ?>active-item<?php } ?>">
						<div class="shop-tree-actions">
							<?= Html::a('<span class="fa fa-folder"></span>', ['option/index', 'OptionSearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]); ?>
							<?= Html::a('<span class="fa fa-pen"></span>', ['category/update', 'id' => $item['category']['id']], ['title' => Yii::t('shop/backend', 'Edit')]) ?>
							<?= Html::a('<span class="fa fa-trash"></span>', ['category/delete', 'id' => $item['category']['id']], ['data' => ['confirm' => Yii::t('shop/backend', 'Are you sure you want to delete this category?'), 'method' => 'post'], 'title' => Yii::t('shop/backend', 'Remove')]) ?>
						</div>
						<div class="shop-tree-link"><?= Html::a($item['category']['name'] . ' (' . $item['category']['count'] . ')', ['option/index', 'OptionSearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]) ?></div>
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

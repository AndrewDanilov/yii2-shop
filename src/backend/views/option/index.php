<?php

use yii\helpers\Html;
use yii\grid\GridView;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\common\models\Option;
use andrewdanilov\shop\common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel andrewdanilov\shop\backend\models\OptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-option-index">

    <p>
        <?= Html::a('Новая опция', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
		        'attribute' => 'category_id',
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
		        'class' => 'yii\grid\ActionColumn',
		        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

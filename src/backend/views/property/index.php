<?php

use yii\helpers\Html;
use yii\grid\GridView;
use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\shop\common\models\Property;
use andrewdanilov\shop\backend\models\PropertySearch;

/* @var $this yii\web\View */
/* @var $searchModel PropertySearch|ValueTypeBehavior */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Свойства';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-attribute-index">

    <p>
        <?= Html::a('Новое свойство', ['create'], ['class' => 'btn btn-success']) ?>
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
            	'attribute' => 'type',
	            'value' => function($model) {
    	            /* @var $model Property */
    	            $typeList = ValueTypeBehavior::getTypeList();
    	            if (isset($typeList[$model->type])) {
		                return $typeList[$model->type];
	                }
    	            return $model->type;
	            },
	            'filter' => ValueTypeBehavior::getTypeList(),
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

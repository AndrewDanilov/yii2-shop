<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel andrewdanilov\shop\backend\models\RelationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Связи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-relation-index">

    <p>
        <?= Html::a('Новая связь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
	        [
		        'attribute' => 'id',
		        'headerOptions' => ['width' => 100],
	        ],
            'key',
            'name',

	        [
		        'class' => 'yii\grid\ActionColumn',
		        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

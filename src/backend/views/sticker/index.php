<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel andrewdanilov\shop\backend\models\StickerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Стикеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-brand-index">

    <p>
        <?= Html::a('Новый стикер', ['update'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
	        [
		        'attribute' => 'id',
		        'headerOptions' => ['width' => 100],
	        ],
	        [
		        'attribute' => 'image',
		        'format' => 'raw',
		        'headerOptions' => ['style' => 'width:100px'],
		        'value' => function($model) { return Html::img($model->image, ['width' => '100']); },
	        ],
	        'label',
	        'order',

	        [
		        'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
		        'template' => '{update}{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel andrewdanilov\shop\backend\models\PaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Способы оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pay-index">

    <p>
        <?= Html::a('Новый способ оплаты', ['create'], ['class' => 'btn btn-success']) ?>
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
            'order',

	        [
		        'class' => 'yii\grid\ActionColumn',
		        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
	        ],
        ],
    ]); ?>
</div>

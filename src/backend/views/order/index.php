<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use andrewdanilov\shop\common\models\Order;
use andrewdanilov\shop\common\models\Pay;
use andrewdanilov\shop\common\models\Delivery;
use andrewdanilov\shop\backend\models\OrderSearch;

/* @var $this yii\web\View */
/* @var $searchModel OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-order-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
	        [
		        'attribute' => 'id',
		        'headerOptions' => ['width' => 100],
	        ],
	        [
		        'attribute' => 'created_at',
		        'format' => 'raw',
		        'filter' => DatePicker::widget([
			        'model' => $searchModel,
			        'attribute' => 'created_at',
			        'language' => 'ru',
			        'template' => '{input}{addon}',
			        'clientOptions' => [
				        'autoclose' => true,
				        'format' => 'dd.mm.yyyy',
				        'clearBtn' => true,
				        'todayBtn' => 'linked',
			        ],
			        'clientEvents' => [
				        'clearDate' => 'function (e) {$(e.target).find("input").change();}',
			        ],
		        ]),
	        ],
            [
            	'attribute' => 'addressee',
	            'format' => 'raw',
	            'value' => 'addresseeStr',
            ],
            [
            	'attribute' => 'address',
	            'value' => 'addressStr',
                'filter' => '',
            ],
            [
            	'attribute' => 'pay_id',
	            'value' => 'pay.name',
	            'filter' => Pay::getPayList(),
	        ],
	        [
		        'attribute' => 'delivery_id',
		        'value' => 'delivery.name',
		        'filter' => Delivery::getDeliveryList(),
	        ],
	        'summ',
            [
            	'attribute' => 'status',
	            'value' => 'statusStr',
	            'filter' => Order::getStatusList(),
            ],

	        [
		        'class' => 'yii\grid\ActionColumn',
		        'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;{cart}&nbsp;&nbsp;&nbsp;&nbsp;{delete}',
		        'contentOptions' => ['style' => 'white-space: nowrap;'],
		        'buttons' => [
		        	'cart' => function ($url, $model) {
				        return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-shopping-cart']), Url::to(['shop-order-products/index', 'id' => $model->id]), ['title' => 'Товары заказа']);
			        },
		        ]
	        ],
        ],
    ]); ?>
</div>

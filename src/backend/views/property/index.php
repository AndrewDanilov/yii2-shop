<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel PropertySearch|ValueTypeBehavior */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\PropertySearch;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Group;
use andrewdanilov\shop\common\models\Property;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = Yii::t('shop/backend', 'Properties');
$this->params['breadcrumbs'][] = $this->title;

ShopAsset::register($this);

$create_property_url = ['property/create'];
if (!empty($propertySearch = array_filter(Yii::$app->request->get('PropertySearch', [])))) {
	$create_property_url['PropertySearch'] = $propertySearch;
}
?>
<div class="shop-attribute-index">

	<div class="shop-editor-actions">
		<?= Html::a(Yii::t('shop/backend', 'New property'), $create_property_url, ['class' => 'btn btn-success']) ?>
	</div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<div class="shop-tree-list">
				<?php foreach ($tree as $item) { ?>
					<div class="shop-list-item level-<?= $item['level'] ?> <?php if (ArrayHelper::getValue($propertySearch, 'category_id') == $item['category']['id']) { ?>active-item<?php } ?>">
						<div class="shop-tree-actions">
							<?= Html::a('<span class="fa fa-folder"></span>', ['property/index', 'PropertySearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]); ?>
							<?= Html::a('<span class="fa fa-pen"></span>', ['category/update', 'id' => $item['category']['id']], ['title' => Yii::t('shop/backend', 'Edit')]) ?>
							<?= Html::a('<span class="fa fa-trash"></span>', ['category/delete', 'id' => $item['category']['id']], ['data' => ['confirm' => Yii::t('shop/backend', 'Are you sure you want to delete this category?'), 'method' => 'post'], 'title' => Yii::t('shop/backend', 'Remove')]) ?>
						</div>
						<div class="shop-tree-link"><?= Html::a($item['category']['name'] . ' (' . $item['category']['count'] . ')', ['property/index', 'PropertySearch' => ['category_id' => $item['category']['id']]], ['title' => Yii::t('shop/backend', 'Open')]) ?></div>
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
			'unit',
			'is_filtered:boolean',
			[
				'attribute' => 'filter_type',
				'value' => function ($model) {
					/* @var $model Property */
					if ($model->is_filtered && in_array($model->type, [ValueTypeBehavior::VALUE_TYPE_STRING, ValueTypeBehavior::VALUE_TYPE_INTEGER])) {
						$filter_types = Property::getFilterTypes();
						if (isset($filter_types[$model->filter_type])) {
							return $filter_types[$model->filter_type];
						}
					}
					return '';
				}
			],
			[
				'attribute' => 'category_ids',
				'value' => function($model) {
					/* @var $model Property */
					return $model->categoriesDelimitedString();
				},
				'filter' => NestedCategoryHelper::getDropdownTree(Category::find()),
				'filterOptions' => ['style' => 'font-family:monospace;'],
			],
			[
				'attribute' => 'group_ids',
				'value' => function($model) {
					/* @var $model Property */
					return $model->groupsDelimitedString();
				},
				'filter' => Group::getGroupList(),
			],
			'order',

			[
				'class' => 'andrewdanilov\gridtools\FontawesomeActionColumn',
				'template' => '{update}{delete}',
				'contentOptions' => ['style' => 'white-space: nowrap;'],
			],
		],
	]); ?>
</div>

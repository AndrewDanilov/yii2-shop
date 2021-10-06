<?php

/* @var $this yii\web\View */
/* @var $tree array */
/* @var $searchModel PropertySearch|ValueTypeBehavior */
/* @var $dataProvider ActiveDataProvider */

use andrewdanilov\behaviors\ValueTypeBehavior;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\assets\ShopAsset;
use andrewdanilov\shop\backend\models\PropertySearch;
use andrewdanilov\shop\backend\widgets\CategoryTree\CategoryTreeFilterList;
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

$create_category_url = ['category/update'];
$create_property_url = ['property/create'];
if (!empty($propertySearch = array_filter(Yii::$app->request->get('PropertySearch', [])))) {
	$create_property_url['PropertySearch'] = $propertySearch;
}
?>
<div class="shop-attribute-index">

	<div class="shop-editor-actions">
		<?= Html::a(Yii::t('shop/backend', 'New property'), $create_property_url, ['class' => 'btn btn-success']) ?>
		<?= Html::a(Yii::t('shop/backend', 'New product category'), $create_category_url, ['class' => 'btn btn-primary']) ?>
	</div>

	<div class="shop-editor-boxes">
		<div class="shop-editor-box">
			<?= CategoryTreeFilterList::widget([
				'tree' => $tree,
				'filteredItemsListUriAction' => 'property/index',
				'filteredItemsListUriParamName' => 'PropertySearch',
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

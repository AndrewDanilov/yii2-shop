<?php
namespace andrewdanilov\shop\backend\controllers;

use andrewdanilov\behaviors\ImagesBehavior;
use andrewdanilov\behaviors\LinkedProductsBehavior;
use andrewdanilov\behaviors\ShopOptionBehavior;
use andrewdanilov\behaviors\TagBehavior;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionHtml;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\ProductCategories;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

class ProductController extends BaseController
{
	/**
	 * @return string
	 */
	public function actionIndex()
	{
		$pages_query = (new Query())
			->select('COUNT(*)')
			->from(ProductCategories::tableName())
			->where(ProductCategories::tableName() . '.category_id = ' . Category::tableName() . '.id');
		$categories_query = Category::find()
			->select([
				Category::tableName() . '.id',
				Category::tableName() . '.parent_id',
				Category::tableName() . '.name',
				'count' => $pages_query,
			])
			->orderBy(['order' => SORT_ASC]);
		$tree = NestedCategoryHelper::getPlaneTree($categories_query);

		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'tree' => $tree,
		]);
	}

	/**
	 * @param int|null $id
	 * @return string|\yii\web\Response
	 */
	public function actionUpdate($id=null)
	{
		/* @var $model Product|ImagesBehavior|TagBehavior|ShopOptionBehavior|LinkedProductsBehavior */

		if ($id) {
			$model = Product::findOne($id);
		} else {
			$model = new Product();

			$productSearch = Yii::$app->request->get('ProductSearch');
			if (!empty($productSearch['category_id'])) {
				$model->setTagIds([$productSearch['category_id']]);
			}
			if (!empty($productSearch['brand_id'])) {
				$model->brand_id = $productSearch['brand_id'];
			}
		}

		$model->getBehavior('properties')->optionsFilter = ArrayHelper::map($model->availableProperties, 'id', 'id');
		$model->getBehavior('options')->optionsFilter = ArrayHelper::map($model->availableOptions, 'id', 'id');

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * @param integer $id
	 * @return \yii\web\Response
	 */
	public function actionDelete($id)
	{
		$model = Product::findOne($id);
		if ($model !== null) {
			$model->delete();
		}

		return $this->redirect(['index']);
	}

	//////////////////////////////////////////////////////////////////

	/**
	 * Возвращает html код группы полей для добавления новой опции к товару.
	 *
	 * @param $optionId - ID опции
	 * @return string
	 */
	public function actionOptionGroup($optionId)
	{
		if (Yii::$app->request->isAjax) {
			if (Yii::$app->request->isPost) {

				return ProductOptionHtml::widget(['optionId' => $optionId]);

			}
		}
		throw new BadRequestHttpException("Error request");
	}
}

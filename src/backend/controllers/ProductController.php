<?php
namespace andrewdanilov\shop\backend\controllers;

use andrewdanilov\behaviors\TagBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionHtml;

class ProductController extends BaseController
{
	/**
	 * @return mixed
	 */
	public function actionIndex()
	{
		$tree = NestedCategoryHelper::getPlaneTree(Category::find());

		$searchModel = new ProductSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'tree' => $tree,
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * @param int|null $id
	 * @return mixed
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
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = Product::findOne($id);
		if ($model !== null) {
			$model->delete();
		}

		return $this->redirect(['index']);
	}

	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param int|null $id
	 * @return mixed
	 */
	public function actionUpdateCategory($id=null)
	{
		if ($id) {
			$model = Category::findOne($id);
		} else {
			$model = new Category();

			$productSearch = Yii::$app->request->get('ProductSearch');
			if (!empty($productSearch['category_id'])) {
				$searchedCategory = Category::findOne($productSearch['category_id']);
				if ($searchedCategory !== null) {
					$model->parent_id = $searchedCategory->parent_id;
				}
			}
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'ProductSearch' => ['category_id' => $model->id]]);
		}

		return $this->render('update-category', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Category model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDeleteCategory($id)
	{
		$model = Category::findOne($id);
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

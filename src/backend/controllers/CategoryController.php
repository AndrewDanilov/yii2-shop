<?php
namespace andrewdanilov\shop\backend\controllers;

use andrewdanilov\shop\common\models\Category;
use Yii;

class CategoryController extends BaseController
{
	/**
	 * Updates an existing Category model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param int|null $id
	 * @return string|\yii\web\Response
	 */
	public function actionUpdate($id=null)
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
			return $this->redirect(['product/index', 'ProductSearch' => ['category_id' => $model->id]]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Category model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return \yii\web\Response
	 */
	public function actionDelete($id)
	{
		$model = Category::findOne($id);
		if ($model !== null) {
			$model->delete();
		}

		return $this->redirect(['product/index']);
	}
}

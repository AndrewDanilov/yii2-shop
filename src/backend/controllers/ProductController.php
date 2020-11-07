<?php
namespace andrewdanilov\shop\backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\backend\models\ProductSearch;
use andrewdanilov\shop\backend\widgets\ProductOptions\ProductOptionHtml;

class ProductController extends BaseController
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

	    $model->getBehavior('properties')->optionsFilter = ArrayHelper::map($model->availableProperties, 'id', 'id');
	    $model->getBehavior('options')->optionsFilter = ArrayHelper::map($model->availableOptions, 'id', 'id');

	    if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

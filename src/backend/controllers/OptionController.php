<?php
namespace andrewdanilov\shop\backend\controllers;

use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\models\OptionSearch;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\CategoryOptions;
use andrewdanilov\shop\common\models\Option;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class OptionController extends BaseController
{
    /**
     * Lists all Option models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $pages_query = (new Query())
		    ->select('COUNT(*)')
		    ->from(CategoryOptions::tableName())
		    ->where(CategoryOptions::tableName() . '.category_id = ' . Category::tableName() . '.id');
	    $categories_query = Category::find()
		    ->select([
			    Category::tableName() . '.id',
			    Category::tableName() . '.parent_id',
			    Category::tableName() . '.name',
			    'count' => $pages_query,
		    ])
		    ->orderBy(['order' => SORT_ASC]);
	    $tree = NestedCategoryHelper::getPlaneTree($categories_query);

        $searchModel = new OptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	        'tree' => $tree,
        ]);
    }

    /**
     * Creates a new Option model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Option();
        $model->is_filtered = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Option model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
     * Finds the Option model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Option the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Option::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php
namespace andrewdanilov\shop\backend\controllers;

use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\backend\models\PropertySearch;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\CategoryProperties;
use andrewdanilov\shop\common\models\Property;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class PropertyController extends BaseController
{
    /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionIndex()
    {
	    $pages_query = (new Query())
		    ->select('COUNT(*)')
		    ->from(CategoryProperties::tableName())
		    ->where(CategoryProperties::tableName() . '.category_id = ' . Category::tableName() . '.id');
	    $categories_query = Category::find()
		    ->select([
			    Category::tableName() . '.id',
			    Category::tableName() . '.parent_id',
			    Category::tableName() . '.name',
			    'count' => $pages_query,
		    ])
		    ->orderBy(['order' => SORT_ASC]);
	    $tree = NestedCategoryHelper::getPlaneTree($categories_query);

        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
	        'tree' => $tree,
        ]);
    }

    /**
     * Creates a new Property model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Property();
	    $model->is_filtered = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Property model.
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
     * Deletes an existing Property model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

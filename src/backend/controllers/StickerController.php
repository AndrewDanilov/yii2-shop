<?php
namespace andrewdanilov\shop\backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use andrewdanilov\shop\common\models\Sticker;
use andrewdanilov\shop\backend\models\StickerSearch;

class StickerController extends Controller
{
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StickerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer|null $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id=null)
    {
        if ($id === null) {
            $model = new Sticker();
        } else {
            $model = $this->findModel($id);
        }

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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Sticker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sticker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
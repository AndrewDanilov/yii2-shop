<?php
namespace andrewdanilov\shop\frontend\controllers;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use andrewdanilov\shop\common\models\Product;
use andrewdanilov\shop\common\models\ProductProperties;
use andrewdanilov\shop\common\models\Category;

// todo
class CatalogController extends Controller
{
	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionCategory($id)
	{
		/* @var $category Category */
		$category = Category::find()->where(['id' => $id])->one();

		$category_products_query = $category->getAllChildrenProducts();
		$form_properties = ArrayHelper::getValue(Yii::$app->request->queryParams, 'Filter.property');
		if ($form_properties !== null) {
			foreach ($form_properties as $property_id => $property_value) {
				if ($property_value) {
					$category_products_query->andWhere((new Query)->select(['id'])->from(ProductProperties::tableName())->where([
						ProductProperties::tableName() . '.product_id' => (new Expression(Product::tableName() . '.id')),
						ProductProperties::tableName() . '.property_id' => $property_id,
						ProductProperties::tableName() . '.value' => $property_value,
					]));
				}
			}
		}
		return $this->render('category', [
			'category' => $category,
			'category_products' => $category_products_query->all(),
		]);
	}

	public function actionProduct($id)
	{
		$product = Product::find()->where(['id' => $id])->one();
		return $this->render('product', [
			'product' => $product,
		]);
	}
}
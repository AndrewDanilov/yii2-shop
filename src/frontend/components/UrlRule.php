<?php
namespace andrewdanilov\shop\frontend\components;

use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
use andrewdanilov\helpers\NestedCategoryHelper;
use andrewdanilov\shop\common\models\Category;
use andrewdanilov\shop\common\models\Product;

class UrlRule extends BaseObject implements UrlRuleInterface
{
	public function createUrl($manager, $route, $params)
	{
		if ($route === 'catalog/index') {
			return 'catalog';
		} elseif ($route === 'catalog/category') {
			if (!empty($params['id'])) {
				$path = NestedCategoryHelper::getCategoryPath(Category::find(), $params['id'], 'slug');
				if (!empty($path)) {
					unset($params['id']);
					$route = 'catalog/' . $path;
					if (!empty($params) && ($query = http_build_query($params)) !== '') {
						$route .= '?' . $query;
					}
					return $route;
				}
			}
		} elseif ($route === 'catalog/product') {
			if (!empty($params['id'])) {
				/* @var $product Product */
				$product = Product::find()->where(['id' => $params['id']])->one();
				if (!empty($product)) {
					$route = 'catalog/' . $product->slug;
					if (!empty($params) && ($query = http_build_query($params)) !== '') {
						$route .= '?' . $query;
					}
					return $route;
				}
			}
		}
		return false;
	}

	/**
	 * @param \yii\web\UrlManager $manager
	 * @param \yii\web\Request $request
	 * @return array|bool
	 * @throws \yii\base\InvalidConfigException
	 */
	public function parseRequest($manager, $request)
	{
		$pathInfo = $request->getPathInfo();
		if ($pathInfo === 'catalog') {
			return ['catalog/index', []];
		} elseif (preg_match('~^catalog(/[\w\-]+)+$~', $pathInfo, $matches)) {
			$path = explode('/', trim($matches[0], '/'));
			$path = array_slice($path, 1);
			// product: single slug in the path
			if (count($path) === 1) {
				$product = Product::findOne([
					'slug' => $path[0],
				]);
				if ($product) {
					return ['catalog/product', ['id' => $product->id]];
				}
			}
			// category: we need to go though all nested categories chain from parent to last child
			$parent_id = 0;
			foreach ($path as $index => $path_item) {
				$category = Category::findOne([
					'slug' => $path_item,
					'parent_id' => $parent_id,
				]);
				if ($category) {
					// category exists, store it and go to the next child
					$parent_id = $category->id;
				} else {
					return false;
				}
			}
			// last $parent_id represents category with no childs, it's destination category
			return ['catalog/category', ['id' => $parent_id]];
		}
		return false;
	}
}

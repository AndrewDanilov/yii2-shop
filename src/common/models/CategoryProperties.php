<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\helpers\NestedCategoryHelper;
use Yii;

/**
 * This is the model class for table "shop_category_properties".
 *
 * @property int $id
 * @property int $property_id
 * @property int $category_id
 */
class CategoryProperties extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_category_properties';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['property_id', 'category_id'], 'required'],
            [['property_id', 'category_id'], 'integer'],
            [['property_id', 'category_id'], 'unique', 'targetAttribute' => ['property_id', 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('shop/common', 'ID'),
            'category_id' => Yii::t('shop/common', 'Category ID'),
            'property_id' => Yii::t('shop/common', 'Property ID'),
        ];
    }

	/**
	 * Возвращает ID's свойств товаров доступные в данной категории,
	 * то есть свойств данной категории и свойств всей цепочки
	 * родительских категорий.
	 * В $category_ids можно передавать ID's дочерних категорий,
	 * родительские при этом будут определены автоматически и добавлены
	 * в массив $category_ids
	 *
	 * @param int[] $category_ids
	 * @return int[]
	 */
    public static function getAvailablePropertyIds($category_ids)
    {
	    $available_category_ids = Category::addParentCategoryIds($category_ids);
	    $available_property_ids = self::find()->select(['property_id'])->where(['category_id' => $available_category_ids])->column();
	    return array_unique($available_property_ids);
    }
}

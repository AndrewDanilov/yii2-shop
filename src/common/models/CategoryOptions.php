<?php
namespace andrewdanilov\shop\common\models;

use andrewdanilov\helpers\NestedCategoryHelper;
use Yii;

/**
 * This is the model class for table "shop_category_options".
 *
 * @property int $id
 * @property int $option_id
 * @property int $category_id
 */
class CategoryOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_category_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_id', 'category_id'], 'required'],
            [['option_id', 'category_id'], 'integer'],
            [['option_id', 'category_id'], 'unique', 'targetAttribute' => ['option_id', 'category_id']],
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
            'option_id' => Yii::t('shop/common', 'Option ID'),
        ];
    }

	/**
	 * Возвращает ID's опций товаров доступные в данной категории,
	 * то есть опций данной категории и опций всей цепочки
	 * родительских категорий.
	 * В $category_ids можно передавать ID's дочерних категорий,
	 * родительские при этом будут определены автоматически и добавлены
	 * в массив $category_ids
	 *
	 * @param int[] $category_ids
	 * @return int[]
	 */
	public static function getAvailableOptionIds($category_ids)
	{
		$available_category_ids = Category::addParentCategoryIds($category_ids);
		$available_option_ids = self::find()->select(['option_id'])->where(['category_id' => $available_category_ids])->column();
		return array_unique($available_option_ids);
	}
}

<?php
namespace andrewdanilov\shop\common\models;

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
}

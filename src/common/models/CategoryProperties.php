<?php
namespace andrewdanilov\shop\common\models;

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
}

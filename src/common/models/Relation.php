<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_relation".
 *
 * @property int $id
 * @property string $key
 * @property string $name
 */
class Relation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['key', 'name'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Ключ',
            'name' => 'Название',
        ];
    }

    //////////////////////////////////////////////////////////////////

    public static function getRelationByKey($key)
    {
    	return static::findOne(['key' => $key]);
    }
}

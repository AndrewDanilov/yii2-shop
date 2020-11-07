<?php
namespace andrewdanilov\shop\common\models;

/**
 * This is the model class for table "shop_product_relations".
 *
 * @property int $id
 * @property int $relation_id
 * @property int $product_id
 * @property int $linked_product_id
 */
class ProductRelations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_product_relations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relation_id', 'product_id', 'linked_product_id'], 'required'],
            [['relation_id', 'product_id', 'linked_product_id'], 'integer'],
            [['relation_id', 'product_id', 'linked_product_id'], 'unique', 'targetAttribute' => ['relation_id', 'product_id', 'linked_product_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'relation_id' => 'Relation ID',
            'product_id' => 'Product ID',
            'linked_product_id' => 'Linked Product ID',
        ];
    }

    public function getProduct()
    {
    	return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getLinkedProduct()
    {
    	return $this->hasOne(Product::class, ['id' => 'linked_product_id']);
    }
}

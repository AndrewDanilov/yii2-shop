<?php

use yii\db\Migration;

/**
 * Class m181113_151133_shop
 */
class m181113_151133_shop extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }

	    // Категория
	    $this->createTable('shop_category', [
		    'id' => $this->primaryKey(),
		    'parent_id' => $this->integer()->notNull()->defaultValue(0),
		    'image' => $this->string(),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
		    'description' => $this->text(),
		    'seo_title' => $this->string(),
		    'seo_description' => $this->text(),
	    ], $tableOptions);

	    // Бренд
	    $this->createTable('shop_brand', [
		    'id' => $this->primaryKey(),
		    'image' => $this->string(),
		    'is_favorite' => $this->tinyInteger(1)->notNull()->defaultValue(0),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
		    'description' => $this->text(),
		    'seo_title' => $this->string(),
		    'seo_description' => $this->text(),
	    ], $tableOptions);

	    // Товар
	    $this->createTable('shop_product', [
		    'id' => $this->primaryKey(),
		    'brand_id' => $this->integer(),
		    'article' => $this->string(),
		    'price' => $this->decimal(10, 2),
		    'discount' => $this->tinyInteger(),
		    'is_new' => $this->tinyInteger(1)->notNull()->defaultValue(0),
		    'is_popular' => $this->tinyInteger(1)->notNull()->defaultValue(0),
		    'is_action' => $this->tinyInteger(1)->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
		    'description' => $this->text(),
		    'seo_title' => $this->string(),
		    'seo_description' => $this->text(),
	    ], $tableOptions);

	    // Товар - индекс
	    $this->createIndex(
		    'idx-shop_product-brand_id',
		    'shop_product',
		    'brand_id'
	    );

	    // Товар - индекс
	    $this->createIndex(
		    'idx-shop_product-is_new',
		    'shop_product',
		    'is_new'
	    );

	    // Товар - индекс
	    $this->createIndex(
		    'idx-shop_product-is_popular',
		    'shop_product',
		    'is_popular'
	    );

	    // Товар - индекс
	    $this->createIndex(
		    'idx-shop_product-is_action',
		    'shop_product',
		    'is_action'
	    );

	    // Фотографии товаров
	    $this->createTable('shop_product_images', [
		    'id' => $this->primaryKey(),
		    'product_id' => $this->integer()->notNull(),
		    'image' => $this->string(),
		    'order' => $this->integer()->notNull()->defaultValue(0),
	    ], $tableOptions);

	    // Фотографии товаров - индекс
	    $this->createIndex(
		    'idx-shop_product_images-product_id',
		    'shop_product_images',
		    'product_id'
	    );

	    // Категории товаров
	    $this->createTable('shop_product_categories', [
		    'id' => $this->primaryKey(),
		    'product_id' => $this->integer()->notNull(),
		    'category_id' => $this->integer()->notNull(),
	    ], $tableOptions);

	    // Категории товаров - уникальный индекс
	    $this->createIndex(
		    'ux-shop_product_categories-product_id-category_id',
		    'shop_product_categories',
		    'product_id, category_id',
		    true
	    );

	    // Опция
	    $this->createTable('shop_option', [
		    'id' => $this->primaryKey(),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
	    ], $tableOptions);

	    // Связь опций и категорий
	    $this->createTable('shop_category_options', [
		    'id' => $this->primaryKey(),
		    'option_id' => $this->integer()->notNull(),
		    'category_id' => $this->integer()->notNull(),
	    ], $tableOptions);

	    // Связь опций и категорий - уникальный индекс
	    $this->createIndex(
		    'ux-shop_category_options-option_id-category_id',
		    'shop_category_options',
		    'option_id, category_id',
		    true
	    );

	    // Связь опций и товаров
	    $this->createTable('shop_product_options', [
		    'id' => $this->primaryKey(),
		    'option_id' => $this->integer()->notNull(),
		    'product_id' => $this->integer()->notNull(),
		    'add_price' => $this->decimal(10, 2)->comment('Добавочная стоимость товара с данной опцией'),
		    'value' => $this->string()->notNull(),
	    ], $tableOptions);

	    // Связь опций и товаров - индекс
	    $this->createIndex(
		    'idx-shop_product_options-option_id-product_id',
		    'shop_product_options',
		    'option_id, product_id'
	    );

	    // Свойство
	    $this->createTable('shop_property', [
		    'id' => $this->primaryKey(),
		    'type' => $this->string(10)->notNull(),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
	    ], $tableOptions);

	    // Связь свойств и категорий
	    $this->createTable('shop_category_properties', [
		    'id' => $this->primaryKey(),
		    'category_id' => $this->integer()->notNull(),
		    'property_id' => $this->integer()->notNull(),
	    ], $tableOptions);

	    // Связь свойств и категорий - уникальный индекс
	    $this->createIndex(
		    'ux-shop_category_properties-property_id-category_id',
		    'shop_category_properties',
		    'property_id, category_id',
		    true
	    );

	    // Связь свойств и товаров
	    $this->createTable('shop_product_properties', [
		    'id' => $this->primaryKey(),
		    'product_id' => $this->integer()->notNull(),
		    'property_id' => $this->integer()->notNull(),
		    'value' => $this->text(),
	    ], $tableOptions);

	    // Связь свойств и товаров - уникальный индекс
	    $this->createIndex(
		    'ux-shop_product_properties-property_id-product_id',
		    'shop_product_properties',
		    'property_id, product_id',
		    true
	    );

	    // Заказ
	    $this->createTable('shop_order', [
		    'id' => $this->primaryKey(),
		    'created_at' => $this->dateTime(),
		    'addressee_name' => $this->string(),
		    'addressee_email' => $this->string(),
		    'addressee_phone' => $this->string(),
		    'address' => $this->text(),
		    'pay_id' => $this->integer()->notNull(),
		    'delivery_id' => $this->string()->notNull(),
		    'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
	    ], $tableOptions);

	    // Заказ - индекс
	    $this->createIndex(
		    'idx-shop_order-created_at',
		    'shop_order',
		    'created_at'
	    );

	    // Заказ - индекс
	    $this->createIndex(
		    'idx-shop_order-status',
		    'shop_order',
		    'status'
	    );

	    // Позиции заказа
	    $this->createTable('shop_order_products', [
		    'id' => $this->primaryKey(),
		    'order_id' => $this->integer()->notNull(),
		    'product_id' => $this->integer()->notNull(),
		    'name' => $this->string()->notNull(),
		    'price' => $this->decimal(10, 2),
		    'count' => $this->integer(),
		    'options' => $this->text(),
	    ], $tableOptions);

	    // Позиции заказа - индекс
	    $this->createIndex(
		    'idx-shop_order_products-order_id',
		    'shop_order_products',
		    'order_id'
	    );

	    // Позиции заказа - индекс
	    $this->createIndex(
		    'idx-shop_order_products-product_id',
		    'shop_order_products',
		    'product_id'
	    );

	    // Доставка
	    $this->createTable('shop_delivery', [
		    'id' => $this->primaryKey(),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
		    'description' => $this->text(),
	    ], $tableOptions);

	    // Оплата
	    $this->createTable('shop_pay', [
		    'id' => $this->primaryKey(),
		    'order' => $this->smallInteger()->notNull()->defaultValue(0),
		    'name' => $this->string()->notNull(),
		    'description' => $this->text(),
	    ], $tableOptions);

	    // Связи
	    $this->createTable('shop_relation', [
		    'id' => $this->primaryKey(),
		    'key' => $this->string()->notNull(),
		    'name' => $this->string()->notNull(),
	    ], $tableOptions);

	    // Связи - уникальный индекс
	    $this->createIndex(
		    'ux-shop_relation-key',
		    'shop_relation',
		    'key',
		    true
	    );

	    // Связи товаров с товарами
	    $this->createTable('shop_product_relations', [
		    'id' => $this->primaryKey(),
		    'relation_id' => $this->integer()->notNull(),
		    'product_id' => $this->integer()->notNull(),
		    'linked_product_id' => $this->integer()->notNull(),
	    ], $tableOptions);

	    // Связи товаров с товарами - уникальный индекс
	    $this->createIndex(
		    'ux-shop_product_relations-rel_id-prod_id-linked_prod_id',
		    'shop_product_relations',
		    'relation_id, product_id, linked_product_id',
		    true
	    );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181113_151133_shop cannot be reverted.\n";

        return false;
    }
}

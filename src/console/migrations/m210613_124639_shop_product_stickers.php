<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m210613_124639_shop_product_stickers
 */
class m210613_124639_shop_product_stickers extends Migration
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

	    $this->createTable('{{%shop_sticker}}', [
	    	'id' => $this->primaryKey(),
		    'label' => $this->string(255),
		    'image' => $this->string(),
		    'order' => $this->integer()->notNull()->defaultValue(0),
	    ], $tableOptions);

	    $this->createTable('{{%shop_product_stickers}}', [
	    	'id' => $this->primaryKey(),
		    'product_id' => $this->integer(),
		    'sticker_id' => $this->integer(),
	    ], $tableOptions);

	    // adding demo stickers
	    $this->insert('{{%shop_sticker}}', [
	    	'id' => 1,
	    	'label' => 'New',
	    ]);
	    $this->insert('{{%shop_sticker}}', [
	    	'id' => 2,
	    	'label' => 'Popular',
	    ]);
	    $this->insert('{{%shop_sticker}}', [
	    	'id' => 3,
	    	'label' => 'Action',
	    ]);

	    // migrating old stickers field values to new ones
	    $products = (new Query)->select(['id', 'is_new', 'is_popular', 'is_action'])->from('{{%shop_product}}')->all();
	    foreach ($products as $product) {
	    	if ($product['is_new']) {
			    $this->insert('{{%shop_product_stickers}}', [
				    'product_id' => $product['id'],
				    'sticker_id' => 1,
			    ]);
		    }
		    if ($product['is_popular']) {
			    $this->insert('{{%shop_product_stickers}}', [
				    'product_id' => $product['id'],
				    'sticker_id' => 2,
			    ]);
		    }
		    if ($product['is_action']) {
			    $this->insert('{{%shop_product_stickers}}', [
				    'product_id' => $product['id'],
				    'sticker_id' => 3,
			    ]);
		    }
	    }

	    $this->addColumn('{{%shop_product}}', 'is_stock', $this->tinyInteger(1)->notNull()->unsigned()->defaultValue(1));
	    $this->dropColumn('{{%shop_product}}', 'is_new');
	    $this->dropColumn('{{%shop_product}}', 'is_popular');
	    $this->dropColumn('{{%shop_product}}', 'is_action');

	    $this->createIndex('idx_shop_product_is_stock', '{{%shop_product}}', ['is_stock']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		return false;
    }
}

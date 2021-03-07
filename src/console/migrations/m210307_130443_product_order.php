<?php

use yii\db\Migration;

/**
 * Class m210307_130443_product_order
 */
class m210307_130443_product_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addColumn('shop_product', 'order', $this->integer()->notNull()->defaultValue(500));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropColumn('shop_product', 'order');
    }
}

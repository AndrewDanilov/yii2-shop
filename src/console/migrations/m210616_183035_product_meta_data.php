<?php

use yii\db\Migration;

/**
 * Class m210616_183035_product_meta_data
 */
class m210616_183035_product_meta_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addColumn('{{%shop_product}}', 'meta_data', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropColumn('{{%shop_product}}', 'meta_data');
    }
}

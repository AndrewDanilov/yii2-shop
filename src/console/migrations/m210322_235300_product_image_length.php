<?php

use yii\db\Migration;

/**
 * Class m210322_235300_product_image_length
 */
class m210322_235300_product_image_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->alterColumn('shop_product_images', 'image', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->alterColumn('shop_product_images', 'image', $this->string());
    }
}

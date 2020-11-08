<?php

use yii\db\Migration;

/**
 * Class m201108_115253_brand_category_product_slugs
 */
class m201108_115253_brand_category_product_slugs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('shop_category', 'slug', $this->string(255));
		$this->addColumn('shop_product', 'slug', $this->string(255));
		$this->addColumn('shop_brand', 'slug', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('shop_category', 'slug');
		$this->dropColumn('shop_product', 'slug');
		$this->dropColumn('shop_brand', 'slug');
    }
}

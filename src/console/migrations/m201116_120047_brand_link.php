<?php

use yii\db\Migration;

/**
 * Class m201116_120047_brand_link
 */
class m201116_120047_brand_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addColumn('shop_brand', 'link', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropColumn('shop_brand', 'link');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m210613_113017_category_short_description_and_meta_data
 */
class m210613_113017_category_short_description_and_meta_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $this->addColumn('{{%shop_category}}', 'short_description', $this->text());
	    $this->addColumn('{{%shop_category}}', 'meta_data', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropColumn('{{%shop_category}}', 'short_description');
	    $this->dropColumn('{{%shop_category}}', 'meta_data');
    }
}

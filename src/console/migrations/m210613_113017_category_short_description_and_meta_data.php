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
	    $this->addColumn('{{%collection}}', 'short_description', $this->text());
	    $this->addColumn('{{%collection}}', 'meta_data', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropColumn('{{%collection}}', 'short_description');
	    $this->dropColumn('{{%collection}}', 'meta_data');
    }
}

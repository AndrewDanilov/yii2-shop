<?php

use yii\db\Migration;

/**
 * Class m210619_081600_shop_product_property_filter_type_and_units
 */
class m210619_081600_shop_product_property_filter_type_and_units extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{%shop_property}}', 'filter_type', $this->string(10)->notNull()->defaultValue('checkboxes')->comment('Way to display property values in search filter: checkboxes|list|interval'));
    	$this->addColumn('{{%shop_property}}', 'unit', $this->string()->notNull()->defaultValue('')->comment('Unit of measure'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_property}}', 'filter_type');
        $this->dropColumn('{{%shop_property}}', 'unit');
    }
}

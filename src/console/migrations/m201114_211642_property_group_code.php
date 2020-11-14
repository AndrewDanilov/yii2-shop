<?php

use yii\db\Migration;

/**
 * Class m201114_211642_property_group_code
 */
class m201114_211642_property_group_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('shop_group', 'code', $this->string(255)->notNull()->defaultValue(''));
		$this->alterColumn('shop_group', 'code', $this->string(255)->notNull());
   		$this->dropTable('shop_option_groups');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }

   		$this->dropColumn('shop_group', 'code');
	    $this->createTable('shop_option_groups', [
		    'id' => $this->primaryKey(),
		    'group_id' => $this->integer()->notNull(),
		    'option_id' => $this->integer()->notNull(),
	    ], $tableOptions);
    }
}

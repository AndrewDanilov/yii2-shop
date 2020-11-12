<?php

use yii\db\Migration;

/**
 * Class m201112_092006_property_groups
 */
class m201112_092006_property_groups extends Migration
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

		// Группы
		$this->createTable('shop_group', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'order' => $this->integer()->notNull()->defaultValue(0),
		], $tableOptions);

		// Связь свойств с группами
		$this->createTable('shop_property_groups', [
			'id' => $this->primaryKey(),
			'group_id' => $this->integer()->notNull(),
			'property_id' => $this->integer()->notNull(),
		], $tableOptions);

		// Связь опций с группами
		$this->createTable('shop_option_groups', [
			'id' => $this->primaryKey(),
			'group_id' => $this->integer()->notNull(),
			'option_id' => $this->integer()->notNull(),
		], $tableOptions);

		$this->dropColumn('shop_property', 'is_filtered');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    echo "m201112_092006_property_groups cannot be reverted.\n";

	    return false;
    }
}

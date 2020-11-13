<?php

use yii\db\Migration;

/**
 * Class m201113_133950_property_and_option_is_filtered
 */
class m201113_133950_property_and_option_is_filtered extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('shop_property', 'is_filtered', $this->boolean()->notNull()->defaultValue(0));
		$this->addColumn('shop_option', 'is_filtered', $this->boolean()->notNull()->defaultValue(0));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('shop_property', 'is_filtered');
		$this->dropColumn('shop_option', 'is_filtered');
	}
}

<?php

use yii\db\Migration;

/**
 * Class m201108_200235_property_is_filtered
 */
class m201108_200235_property_is_filtered extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('shop_property', 'is_filtered', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('shop_property', 'is_filtered');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m200221_061559_add_custom_ports_for_console
 */
class m200221_061559_add_custom_ports_for_console extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('setting', ['key' => 'from_port', 'value' => '3000']);
        $this->insert('setting', ['key' => 'to_port', 'value' => '9000']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200221_061559_add_custom_ports_for_console cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200221_061559_add_custom_ports_for_console cannot be reverted.\n";

        return false;
    }
    */
}

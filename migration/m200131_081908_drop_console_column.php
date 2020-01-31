<?php

use yii\db\Migration;

/**
 * Class m200131_081908_drop_console_column
 */
class m200131_081908_drop_console_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('server', 'console_address');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200131_081908_drop_console_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200131_081908_drop_console_column cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m200128_070835_drop_server_license_column
 */
class m200128_070835_drop_server_license_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('server', 'license');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200128_070835_drop_server_license_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200128_070835_drop_server_license_column cannot be reverted.\n";

        return false;
    }
    */
}

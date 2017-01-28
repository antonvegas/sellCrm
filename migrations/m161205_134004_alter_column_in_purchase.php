<?php

use yii\db\Migration;

class m161205_134004_alter_column_in_purchase extends Migration
{
    public function up()
    {
        $this->alterColumn('purchase', 'price', 'float');
    }

    public function down()
    {
        echo "m161205_134004_alter_column_in_purchase cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

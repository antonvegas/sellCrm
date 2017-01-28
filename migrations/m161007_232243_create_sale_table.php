<?php

use yii\db\Migration;

class m161007_232243_create_sale_table extends Migration
{
    public function up()
    {
        $this->createTable('sale', [
            'id' => $this->primaryKey(),
            'updated_at' => 'timestamp on update current_timestamp',
            'created_at' => $this->timestamp()->defaultValue(0),
            'seller_id' => $this->integer(),
            'discount' => $this->integer(),
            'price' => $this->integer(),
        ]);

        $this->addForeignKey('fk-sale-user', 'sale', 'seller_id', 'users', 'id', 'SET NULL');

    }

    public function down()
    {
        $this->dropTable('sale'); 
    }
}

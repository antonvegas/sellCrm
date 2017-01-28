<?php

use yii\db\Migration;

class m161007_232455_create_purchase extends Migration
{
    public function up()
    {
        $this->createTable('purchase', [
            'id' => $this->primaryKey(),
            'updated_at' => 'timestamp on update current_timestamp',
            'created_at' => $this->timestamp()->defaultValue(0),
            'seller_id' => $this->integer(),
            'price' => $this->integer(),
        ]); 

        $this->addForeignKey('fk-purchase-user', 'purchase', 'seller_id', 'users', 'id', 'SET NULL', 'RESTRICT' );
    } 

    public function down()
    {
        $this->dropTable('purchase');
    }
}

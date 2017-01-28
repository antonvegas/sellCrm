<?php

use yii\db\Migration;

class m161007_232510_product_of_purchase extends Migration
{
    public function up()
    {
        $this->createTable('product_of_purchase', [
            'id' => $this->primaryKey(),
            'purchase_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-product_of_purchase-purchase_id', 'product_of_purchase', 'purchase_id');
        $this->addForeignKey('fk-product_of_purchase-purchase', 'product_of_purchase', 'purchase_id', 'purchase', 'id', 'CASCADE', 'RESTRICT' );
        $this->addForeignKey('fk-product_of_purchase-product', 'product_of_purchase', 'product_id', 'product', 'id', 'CASCADE', 'RESTRICT' );
    }

    public function down()
    {
        $this->dropTable('product_of_purchase');
    }

}

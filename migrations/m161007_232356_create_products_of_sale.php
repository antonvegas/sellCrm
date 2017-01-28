<?php

use yii\db\Migration;

class m161007_232356_create_products_of_sale extends Migration
{
    public function up()
    {
        $this->createTable('product_of_sale', [
            'id' => $this->primaryKey(),
            'sale_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-product_of_sale-sale_id', 'product_of_sale', 'sale_id');
        $this->addForeignKey('fk-product_of_sale-sale', 'product_of_sale', 'sale_id', 'sale', 'id', 'CASCADE', 'RESTRICT' );
        $this->addForeignKey('fk-product_of_sale-product', 'product_of_sale', 'product_id', 'product', 'id', 'CASCADE', 'RESTRICT' );

    }

    public function down() 
    {
        $this->dropTable('product_of_sale');
    }
}

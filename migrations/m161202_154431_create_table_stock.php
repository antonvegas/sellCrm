<?php

use yii\db\Migration;

class m161202_154431_create_table_stock extends Migration
{
    public function up()
    {

        $this->createTable('stock', [
            'id' => $this->primaryKey(),
            'article' => $this->string(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
            'priceIn' => $this->float()->notNull(),
            'priceOut' => $this->float()->notNull(),
        ]);

        $this->addColumn('product_of_sale', 'stock_id', $this->integer());
 
        $this->createIndex('idx-stock-article', 'stock', 'article');
        $this->addForeignKey('fk-stock-product', 'stock', 'product_id', 'product', 'id', 'CASCADE', 'RESTRICT' );
        $this->addForeignKey('fk-product_of_sale-stock', 'product_of_sale', 'stock_id', 'stock', 'id', 'SET NULL', 'RESTRICT' );

    }
    
    public function down()
    {
        $this->dropTable('table_stock');
    }
}

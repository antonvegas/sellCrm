<?php

use yii\db\Migration;

class m161007_230112_create_product_table extends Migration
{
    public function up()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'price' => $this->integer()->notNull(),
            'active' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'count' => $this->integer()
        ]);

        $this->createIndex('idx-product-category_id', 'product', 'category_id');
        $this->addForeignKey('fk-product-category', 'product', 'category_id', 'category', 'id', 'SET NULL', 'RESTRICT' );
    }

    public function down()
    { 
        $this->dropTable('product');
    }
}

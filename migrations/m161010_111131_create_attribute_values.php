<?php

use yii\db\Migration;

class m161010_111131_create_attribute_values extends Migration
{
    public function up()
    {
        $this->createTable('product_attribute_value', [
            'product_id' => $this->integer()->notNull(),
            'attribute_id' => $this->integer()->notNull(),
            'value' => $this->string()->notNull(),
        ]);

        $this->addPrimaryKey('pk-product_attribute_value', 'product_attribute_value', ['product_id', 'attribute_id']);

        $this->createIndex('idx-value-product_id', 'product_attribute_value', 'product_id');
        $this->createIndex('idx-value-attribute_id', 'product_attribute_value', 'attribute_id' );
        $this->createIndex('idx-value-value', 'product_attribute_value', 'value' );

        $this->addForeignKey('fk-product_attribute_value-product', 'product_attribute_value', 'product_id', 'product', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-product_attribute_value-attribute', 'product_attribute_value', 'attribute_id', 'product_attribute', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('product_attribute_value');
    }
}

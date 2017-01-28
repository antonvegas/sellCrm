<?php

use yii\db\Migration;

class m161010_111110_create_attributes extends Migration
{
    public function up()
    {
        $this->createTable('product_attribute', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

       
    }

 
    public function down()
    {
        $this->dropTable('product_attribute');
    }
}

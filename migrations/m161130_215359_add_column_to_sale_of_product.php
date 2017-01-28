<?php

use yii\db\Migration;

class m161130_215359_add_column_to_sale_of_product extends Migration
{
    public function up()
    {
        $this->addColumn('product_of_sale', 'article', $this->string());
    }

    public function down()
    {
        $this->dropColumn('product_of_sale', 'article');
    }
}

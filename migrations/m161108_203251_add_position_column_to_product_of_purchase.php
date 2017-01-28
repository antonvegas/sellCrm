<?php

use yii\db\Migration;

class m161108_203251_add_position_column_to_product_of_purchase extends Migration
{
    public function up()
    {
        $this->addColumn('product_of_purchase', 'article', $this->string());
    }

    public function down() 
    {
        $this->dropColumn('product_of_purchase', 'article');
    }
}

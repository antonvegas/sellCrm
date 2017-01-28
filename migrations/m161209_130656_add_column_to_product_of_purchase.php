<?php

use yii\db\Migration;

class m161209_130656_add_column_to_product_of_purchase extends Migration
{
    public function up()
    {
        $this->addColumn('product_of_sale', 'price', $this->float()); 
    }

    public function down()
    {
    }
}

<?php

use yii\db\Migration;

class m161202_153822_drop_column_from_product_change_price_in_product_of_purchase extends Migration
{
    public function up()
    {
        $this->dropColumn('product', 'count');
        $this->alterColumn('product_of_purchase', 'priceIn', 'float');
        $this->alterColumn('product_of_purchase', 'priceOut', 'float');
    }

    public function down()
    {
        //
    }
}

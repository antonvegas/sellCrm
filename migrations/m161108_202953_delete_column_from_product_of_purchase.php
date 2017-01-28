<?php

use yii\db\Migration;

class m161108_202953_delete_column_from_product_of_purchase extends Migration
{
    public function up()
    {
        $this->dropColumn('product', 'article');
    }

    public function down()
    {
        echo "m161108_202953_delete_column_from_product_of_purchase cannot be reverted.\n";

        return false;
    }
    
}

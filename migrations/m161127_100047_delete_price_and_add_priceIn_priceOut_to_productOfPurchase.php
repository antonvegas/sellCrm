<?php

use yii\db\Migration;

class m161127_100047_delete_price_and_add_priceIn_priceOut_to_productOfPurchase extends Migration
{
    public function up()
    {
        $this->addColumn('product_of_purchase', 'priceIn', $this->string());
        $this->addColumn('product_of_purchase', 'priceOut', $this->string());
        $this->dropColumn('product_of_purchase', 'price');
        $this->dropColumn('product', 'price');  
    }

    public function down()
    {
        echo "m161127_100047_delete_price_and_add_priceIn_priceOut_to_productOfPurchase cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

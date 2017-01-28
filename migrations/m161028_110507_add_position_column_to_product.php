<?php

use yii\db\Migration;

class m161028_110507_add_position_column_to_product extends Migration
{
    public function up()
    {
        $this->addColumn('product', 'article', $this->string());
    }

    public function down()
    {
        $this->dropColumn('product', 'article');
    }
}

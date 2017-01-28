<?php

use yii\db\Migration;

class m161028_111424_add_position_column_to_product extends Migration
{
    public function up()
    {
        $this->addColumn('product', 'url_image', $this->string());
    }

    public function down()
    {
        $this->dropColumn('product', 'url_image');
    }
}

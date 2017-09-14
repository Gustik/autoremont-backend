<?php

use yii\db\Migration;

class m170703_041943_add_address_to_page_table extends Migration
{
    public $tableName = 'page';

    public function up()
    {
        $this->addColumn($this->tableName, 'address', $this->string()->notNull()->unique());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'address');
        return true;
    }
}

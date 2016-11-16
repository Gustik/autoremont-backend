<?php

use yii\db\Migration;

class m161116_050810_add_url_field_to_company_table extends Migration
{
    public $tableName = 'company';

    public function up()
    {
        $this->addColumn($this->tableName, 'url', $this->string()->null());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'url');
        return true;
    }
}

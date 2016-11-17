<?php

use yii\db\Migration;

class m161116_060916_add_company_logo_field_to_comapny_table extends Migration
{
    public $tableName = 'company';

    public function up()
    {
        $this->alterColumn($this->tableName, 'phone', $this->string()->null());
        $this->addColumn($this->tableName, 'logo', $this->string()->null());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'logo');
        return true;
    }
}

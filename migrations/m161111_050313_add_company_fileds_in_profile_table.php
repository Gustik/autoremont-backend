<?php

use yii\db\Migration;

class m161111_050313_add_company_fileds_in_profile_table extends Migration
{
    public $tableName = 'profile';

    public function up()
    {
        $this->addColumn($this->tableName, 'company_name', $this->string()->null());
        $this->addColumn($this->tableName, 'company_address', $this->string()->null());
        $this->addColumn($this->tableName, 'company_logo', $this->string()->null());
        $this->addColumn($this->tableName, 'lat', $this->decimal(10, 8)->null());
        $this->addColumn($this->tableName, 'lng', $this->decimal(11, 8)->null());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'company_name');
        $this->dropColumn($this->tableName, 'company_address');
        $this->dropColumn($this->tableName, 'company_logo');
        $this->dropColumn($this->tableName, 'lat');
        $this->dropColumn($this->tableName, 'lng');
        return true;
    }

}

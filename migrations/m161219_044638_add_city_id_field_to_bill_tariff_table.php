<?php

use yii\db\Migration;

class m161219_044638_add_city_id_field_to_bill_tariff_table extends Migration
{
    public $tableName = 'bill_tariff';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', $this->integer()->defaultValue(1));
        $this->addForeignKey('fk_bill_tariff_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_bill_tariff_city_id', $this->tableName);
        $this->dropColumn($this->tableName, 'city_id');
    }
}

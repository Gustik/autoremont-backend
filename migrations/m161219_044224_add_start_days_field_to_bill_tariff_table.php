<?php

use yii\db\Migration;

class m161219_044224_add_start_days_field_to_bill_tariff_table extends Migration
{
    public $tableName = 'bill_tariff';

    public function up()
    {
        $this->addColumn($this->tableName, 'start_days', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'start_days');
    }
}

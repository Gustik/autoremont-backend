<?php

use yii\db\Migration;

class m161219_032023_add_status_field_to_bill_payment extends Migration
{
    public $tableName = 'bill_payment';

    public function up()
    {
        $this->addColumn($this->tableName, 'status', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'status');
        return true;
    }
}

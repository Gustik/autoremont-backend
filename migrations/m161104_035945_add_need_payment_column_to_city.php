<?php

use yii\db\Migration;

class m161104_035945_add_need_payment_column_to_city extends Migration
{
    public $tableName = 'city';

    public function up()
    {
        $this->addColumn($this->tableName, 'need_payment', $this->boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'need_payment');
        return true;
    }
}

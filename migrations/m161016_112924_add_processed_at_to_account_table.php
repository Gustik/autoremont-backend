<?php

use yii\db\Migration;

class m161016_112924_add_processed_at_to_account_table extends Migration
{
    public $tableName = 'bill_account';

    public function up()
    {
        $this->addColumn($this->tableName, 'processed_at', $this->dateTime()->notNull()->defaultExpression("NOW()"));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'processed_at');
        return true;
    }
}

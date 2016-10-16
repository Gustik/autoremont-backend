<?php

use yii\db\Migration;

/**
 * Handles the creation for table `bill_tariff`.
 */
class m161014_105829_create_bill_tariff_table extends Migration
{
    public $tableName = 'bill_tariff';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'day_cost' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

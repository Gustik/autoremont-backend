<?php

use yii\db\Migration;

/**
 * Handles the creation for table `bill_payment`.
 */
class m161014_110135_create_bill_payment_table extends Migration
{
    public $tableName = 'bill_payment';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'amount' => $this->integer()->notNull(),
            'tariff_id' => $this->integer()->notNull(),
            'days' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_bill_payment_user_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_bill_payment_tariff_id', $this->tableName, 'tariff_id', 'bill_tariff', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

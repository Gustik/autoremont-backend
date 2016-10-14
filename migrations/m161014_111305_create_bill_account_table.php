<?php

use yii\db\Migration;

/**
 * Handles the creation for table `bill_account`.
 */
class m161014_111305_create_bill_account_table extends Migration
{
    public $tableName = 'bill_account';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'days' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_bill_account_user_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

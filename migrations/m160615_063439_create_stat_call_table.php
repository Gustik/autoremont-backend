<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stat_call_table`.
 */
class m160615_063439_create_stat_call_table extends Migration
{
    public $tableName = 'stat_call';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->notNull(),
            'from' => $this->text()->notNull(),
            'to' => $this->text()->notNull(),
            'cat' => $this->integer()->notNull()
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

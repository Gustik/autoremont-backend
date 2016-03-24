<?php

use yii\db\Migration;

class m160324_051234_create_stat_table extends Migration
{
    public $tableName = 'stat';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime()->notNull(),
            'user_total' => $this->integer()->notNull(),
            'user_active' => $this->integer()->notNull(),
            'user_new' => $this->integer()->notNull(),
            'order_total' => $this->integer()->notNull(),
            'order_new' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

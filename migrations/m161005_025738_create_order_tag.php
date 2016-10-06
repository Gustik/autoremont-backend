<?php

use yii\db\Migration;

class m161005_025738_create_order_tag extends Migration
{
    public $tableName = 'order_tag';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'frequency' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

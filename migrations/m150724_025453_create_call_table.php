<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_025453_create_call_table extends Migration
{
    public $tableName = 'call';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'client_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'mech_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'order_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'client_accept' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT false',
            'mech_accept' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT false',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_call_client_id', $this->tableName, 'client_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_call_mech_id', $this->tableName, 'mech_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_call_order_id', $this->tableName, 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_072353_create_city_table extends Migration
{
    public $tableName = 'city';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'name' => Schema::TYPE_STRING.' NOT NULL',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->delete($this->tableName, ['id' => 1]);
        $now = date('Y-m-d H:i:s');
        $admin = [
            'id' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'name' => 'Якутск'
        ];
        $this->insert($this->tableName, $admin);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150521_061704_create_variable_table extends Migration
{
    public $tableName = 'variable';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'name' => Schema::TYPE_STRING.' NOT NULL',
            'param' => Schema::TYPE_STRING.' NOT NULL',
            'value' => Schema::TYPE_STRING.' NOT NULL',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $now = date('Y-m-d H:i:s');
        $values = [
            [
                'created_at' => $now,
                'updated_at' => $now,
                'name' => 'Отправитель СМС',
                'param' => 'sms-sender',
                'value' => 'SMS-Sender'
            ],
            [
                'created_at' => $now,
                'updated_at' => $now,
                'name' => 'Переменная окружения',
                'param' => 'environment',
                'value' => 'DEV'
            ],
        ];
        foreach ($values as $value) {
            $this->insert($this->tableName, $value);
        }
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

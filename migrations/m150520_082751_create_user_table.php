<?php

use yii\db\Schema;
use yii\db\Migration;

class m150520_082751_create_user_table extends Migration
{
    public $tableName = 'user';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'visited_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'login' => Schema::TYPE_STRING.' NOT NULL',
            'password_hash' => Schema::TYPE_STRING.' DEFAULT NULL',
            'access_token' => Schema::TYPE_STRING.' DEFAULT NULL',
            'sms_code' => Schema::TYPE_INTEGER.' DEFAULT NULL',
            'sms_code_time' => Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true',
            'is_admin' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT false'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->delete($this->tableName, ['id' => 1]);
        $now = date('Y-m-d H:i:s');
        $admin = [
            'id' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'visited_at' => $now,
            'login' => 'admin',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'access_token' => Yii::$app->getSecurity()->generatePasswordHash(mt_rand()),
            'is_admin' => true
        ];
        $this->insert($this->tableName, $admin);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

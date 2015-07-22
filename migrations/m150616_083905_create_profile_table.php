<?php

use yii\db\Schema;
use yii\db\Migration;

class m150616_083905_create_profile_table extends Migration
{
    public $tableName = 'profile';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'name' => Schema::TYPE_STRING.' NOT NULL DEFAULT "Аноним"',
            'birth_date' => Schema::TYPE_DATE.' DEFAULT NULL',
            'avatar' => Schema::TYPE_STRING.' DEFAULT "placeholder.png"',
            'user_id' => Schema::TYPE_INTEGER.' NOT NULL UNIQUE',
            'gcm_id' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_brand' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_model' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_color' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_year' => Schema::TYPE_INTEGER.' DEFAULT NULL',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_profile_user_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $admin = [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'name' => 'Администратор',
            'user_id' => 1
        ];
        $this->insert($this->tableName, $admin);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_093331_create_order_table extends Migration
{
    public $tableName = 'order';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'updated_at' => Schema::TYPE_DATETIME.' NOT NULL',
            'description' => Schema::TYPE_TEXT.' NOT NULL',
            'price' => Schema::TYPE_INTEGER.' NOT NULL',
            'car_brand' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_model' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_year' => Schema::TYPE_STRING.' DEFAULT NULL',
            'car_color' => Schema::TYPE_STRING.' DEFAULT NULL',
            'author_id' => Schema::TYPE_INTEGER.' NOT NULL',
            'executor_id' => Schema::TYPE_INTEGER.' DEFAULT NULL',
            'is_active' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT true'
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_order_author_id', $this->tableName, 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

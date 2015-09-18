<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_073247_add_city_id_order_table extends Migration
{
    public $tableName = 'order';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', Schema::TYPE_INTEGER.' NOT NULL DEFAULT 1');
        $this->addForeignKey('fk_order_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'city_id');
        return true;
    }
}

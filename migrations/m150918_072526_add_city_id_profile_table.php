<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_072526_add_city_id_profile_table extends Migration
{
    public $tableName = 'profile';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', Schema::TYPE_INTEGER.' NOT NULL DEFAULT 1');
        $this->addForeignKey('fk_profile_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'city_id');
        return true;
    }
}

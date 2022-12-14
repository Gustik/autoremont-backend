<?php

use yii\db\Migration;

class m161222_014629_add_city_field_to_stat_call_table extends Migration
{
    public $tableName = 'stat_call';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', $this->integer()->defaultValue(1));
        $this->addForeignKey('fk_stat_call_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_stat_call_city_id', $this->tableName);
        $this->dropColumn($this->tableName, 'city_id');
    }
}

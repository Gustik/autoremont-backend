<?php

use yii\db\Migration;

class m170209_111800_add_city_id_field_to_stat_table extends Migration
{
    public $tableName = 'stat';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', $this->integer()->defaultValue(1));
        $this->addForeignKey('fk_stat_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_stat_city_id', $this->tableName);
        $this->dropColumn($this->tableName, 'city_id');
    }
}

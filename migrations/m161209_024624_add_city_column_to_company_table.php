<?php

use yii\db\Migration;

/**
 * Handles adding city to table `company`.
 */
class m161209_024624_add_city_column_to_company_table extends Migration
{
    public $tableName = 'company';

    public function up()
    {
        $this->addColumn($this->tableName, 'city_id', $this->integer()->defaultValue(1));
        $this->addForeignKey('fk_company_city_id', $this->tableName, 'city_id', 'city', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_company_city_id', $this->tableName);
        $this->dropColumn($this->tableName, 'city_id');
        return true;
    }
}

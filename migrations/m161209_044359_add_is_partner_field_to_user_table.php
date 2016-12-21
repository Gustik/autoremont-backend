<?php

use yii\db\Migration;

class m161209_044359_add_is_partner_field_to_user_table extends Migration
{
    public $tableName = 'user';

    public function up()
    {
        $this->addColumn($this->tableName, 'is_partner', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'is_partner');
        return true;
    }
}

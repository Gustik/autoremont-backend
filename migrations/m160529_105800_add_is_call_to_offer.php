<?php

use yii\db\Migration;

/**
 * Handles adding is_call to table `offer`.
 */
class m160529_105800_add_is_call_to_offer extends Migration
{
    public $tableName = 'offer';

    public function up()
    {
        $this->addColumn($this->tableName, 'is_call', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'is_call');
        return true;
    }
}

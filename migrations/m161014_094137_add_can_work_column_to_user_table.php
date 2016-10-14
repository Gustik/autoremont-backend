<?php

use yii\db\Migration;

/**
 * Handles adding can_work to table `user`.
 */
class m161014_094137_add_can_work_column_to_user_table extends Migration
{
    public $tableName = 'user';

    public function up()
    {
        $this->addColumn($this->tableName, 'can_work', $this->boolean()->notNull()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'can_work');
        return true;
    }
}

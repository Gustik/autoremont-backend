<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075520_add_banned_to_user_table extends Migration
{
    public $tableName = 'user';

    public function up()
    {
        $this->addColumn($this->tableName, 'banned_to', Schema::TYPE_DATETIME.' DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'banned_to');
        return true;
    }
}

<?php

use yii\db\Migration;

class m160401_073652_add_topic_to_category extends Migration
{
    public $tableName = 'category';

    public function up()
    {
        $this->addColumn($this->tableName, 'topic', $this->string()->notNull()->defaultValue('mech'));
        $this->update($this->tableName, ['topic' => 'shop'], 'id = 2');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'topic');
        return true;
    }
}

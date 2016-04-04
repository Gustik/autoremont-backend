<?php

use yii\db\Migration;

class m160401_040904_add_category_id_to_order extends Migration
{
    public $tableName = 'order';

    public function up()
    {
        $this->addColumn($this->tableName, 'category_id', $this->integer()->notNull()->defaultValue(1));
        $this->addForeignKey('fk_order_category_id', $this->tableName, 'category_id', 'category', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'category_id');
        return true;
    }
}

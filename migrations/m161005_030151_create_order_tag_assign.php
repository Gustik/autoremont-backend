<?php

use yii\db\Migration;

class m161005_030151_create_order_tag_assign extends Migration
{
    public $tableName = 'order_tag_assign';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'order_id' => $this->integer()->notNull(),
            'order_tag_id' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);

        $this->addForeignKey('fk_order_tag_assign_order_id', $this->tableName, 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_order_tag_assign_order_tag_id', $this->tableName, 'order_tag_id', 'order_tag', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

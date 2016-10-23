<?php

use yii\db\Migration;

/**
 * Handles the creation for table `profile_tag_assign`.
 */
class m161017_060642_create_profile_tag_assign_table extends Migration
{
    public $tableName = 'profile_tag_assign';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'profile_id' => $this->integer()->notNull(),
            'order_tag_id' => $this->integer()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);

        $this->addForeignKey('fk_profile_tag_assign_profile_id', $this->tableName, 'profile_id', 'profile', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_profile_tag_assign_order_tag_id', $this->tableName, 'order_tag_id', 'order_tag', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

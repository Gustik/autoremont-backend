<?php

use yii\db\Migration;

/**
 * Handles the creation for table `offer`.
 */
class m160527_044500_create_offer extends Migration
{
    public $tableName = 'offer';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'text' => $this->text()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'is_new' => $this->boolean()->notNull()->defaultValue(true),
            'is_active' => $this->boolean()->notNull()->defaultValue(true)
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_offer_order_id', $this->tableName, 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_offer_author_id', $this->tableName, 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `review`.
 */
class m161104_064940_create_review_table extends Migration
{
    public $tableName = 'review';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'mech_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'comment' => $this->string()->notNull(),
            'rating' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
        $this->addForeignKey('fk_review_mech_id', $this->tableName, 'mech_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_review_author_id', $this->tableName, 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_review_order_id', $this->tableName, 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

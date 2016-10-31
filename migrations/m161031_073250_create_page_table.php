<?php

use yii\db\Migration;

/**
 * Handles the creation for table `page`.
 */
class m161031_073250_create_page_table extends Migration
{
    public $tableName = 'page';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

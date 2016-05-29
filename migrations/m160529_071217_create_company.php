<?php

use yii\db\Migration;

/**
 * Handles the creation for table `company`.
 */
class m160529_071217_create_company extends Migration
{
    public $tableName = 'company';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $fields = [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'category' => $this->integer()->notNull()->defaultValue(1),
            'name' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true)
        ];
        $this->createTable($this->tableName, $fields, $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
}

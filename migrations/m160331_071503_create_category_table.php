<?php

use yii\db\Migration;

class m160331_071503_create_category_table extends Migration
{
    public $tableName = 'category';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()
        ], $tableOptions);
        $this->insert($this->tableName, ['id' => 1, 'name' => 'Ремонт']);
        $this->insert($this->tableName, ['id' => 2, 'name' => 'Запчасти']);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

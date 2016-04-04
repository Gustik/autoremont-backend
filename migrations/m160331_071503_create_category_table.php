<?php

use yii\db\Migration;

class m160331_071503_create_category_table extends Migration
{
    public $tableName = 'category';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()
        ]);
        $this->insert($this->tableName, ['id' => 1, 'name' => 'Ремонт']);
        $this->insert($this->tableName, ['id' => 2, 'name' => 'Запчасти']);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

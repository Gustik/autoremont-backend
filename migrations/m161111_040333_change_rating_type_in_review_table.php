<?php

use yii\db\Migration;

class m161111_040333_change_rating_type_in_review_table extends Migration
{
    public $tableName = 'review';

    public function up()
    {
        $this->alterColumn($this->tableName, 'rating', $this->float()->notNull());
    }

    public function down()
    {
        $this->alterColumn($this->tableName, 'rating', $this->integer()->notNull());
        return true;
    }
}

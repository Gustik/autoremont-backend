<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount_use`.
 */
class m170402_031025_create_discount_use_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('discount_use', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'discount_company_id' => $this->integer()->notNull(),
            'params' => 'JSON',
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_discount_use_user_id', 'discount_use', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_discount_use_discount_company_id', 'discount_use', 'discount_company_id', 'discount_company', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('discount_use');
    }
}

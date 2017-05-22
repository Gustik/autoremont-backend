<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount_company`.
 */
class m170402_030307_create_discount_company_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('discount_company', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'discount' => $this->string(5),
            'description' => $this->text(),
            'password' => $this->string(),
        ], $tableOptions);
        $this->addForeignKey('fk_discount_company_company_id', 'discount_company', 'company_id', 'company', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('discount_company');
    }
}

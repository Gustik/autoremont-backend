<?php

use yii\db\Migration;

class m161007_032307_create_mech_order_stat_view extends Migration
{
    public $viewName = 'mech_order_stat';

    public function up()
    {
        $sql = "CREATE VIEW mech_activity AS
                SELECT `o`.`author_id` as author_id,
                    MIN(`o`.`created_at`) as `first_action`,
                    MAX(`o`.`created_at`) as `last_action`
                FROM `offer` as `o`
                GROUP BY `o`.`author_id`;

                CREATE VIEW `$this->viewName` AS
                SELECT
                    `user`.`login` as `login`,
                    `profile`.`name` as `name`,
                    `profile`.`birth_date` as `birth_date`,
                    COUNT(`order`.`id`) as `orders_count`,
                    `order`.`category_id` as `category_id`,
                    `mech_activity`.`first_action` as `first_action`,
                    `mech_activity`.`last_action` as `last_action`

                FROM `user`

                INNER JOIN `profile` on `user`.`id` = `profile`.`user_id`
                INNER JOIN `offer` on `user`.`id` = `offer`.`author_id`
                INNER JOIN `order` on `order`.`id` = `offer`.`order_id`
                INNER JOIN `mech_activity` on `mech_activity`.`author_id` = `user`.`id`

                GROUP BY `user`.`login`, `profile`.`name`, `profile`.`birth_date`, `first_action`, `last_action`, `order`.`category_id`";

        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("DROP VIEW $this->viewName");
        $this->execute("DROP VIEW mech_activity");
        return true;
    }
}

<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class BillAccountController extends Controller
{
    public function actionProceed()
    {
        $sql = '
            SET @diff := 0;
            UPDATE bill_account AS ba, user AS u
            SET
                ba.processed_at = IF((@diff:=DATEDIFF(NOW(), processed_at)) > 0, NOW(), ba.processed_at),
                ba.days = IF(ba.days <= 0, 0, ba.days - @diff),
                u.can_work = IF(ba.days <= 0, false, true)
            WHERE
                ba.user_id = u.id
         ';
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);

        return $command->execute();
    }
}

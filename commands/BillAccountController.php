<?php

namespace app\commands;

use app\models\BillAccount;
use Yii;
use yii\console\Controller;

class BillAccountController extends Controller
{
    public function actionProceed()
    {
        return static::decrementDay();
    }

    public static function decrementDay()
    {
        $sql = '
            SET @diff := 0, @day = 1440;
            UPDATE bill_account AS ba, user AS u
            SET
                ba.processed_at = IF((@diff:=TIMESTAMPDIFF(MINUTE, ba.processed_at, NOW())) >= @day, NOW(), ba.processed_at),
                ba.days = IF(ba.days <= 0, 0, IF(@diff >= @day, ba.days - (@diff div @day), ba.days)),
                u.can_work = IF(ba.days <= 0, false, true)
            WHERE
                ba.user_id = u.id
         ';
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);

        return $command->execute();
    }
}

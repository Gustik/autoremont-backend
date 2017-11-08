<?php

namespace app\commands;

use app\models\BillAccount;
use DateTime;
use yii\console\Controller;
use yii\console\Exception;
use yii\db\Query;
use yii\web\NotAcceptableHttpException;

class BillAccountController extends Controller
{
    public function actionProceed()
    {
        return static::decrementDay();
    }

    /**
     * @param $startDate string YYYY-MM-DD H:i:s Format
     * @param $endDate string YYYY-MM-DD H:i:s Format
     *
     * @return int count of diff days
     */
    public static function diffDays($startDate, $endDate)
    {
        $datetime1 = new DateTime($startDate);
        $datetime2 = new DateTime($endDate);
        $interval = $datetime1->diff($datetime2, false);

        return intval($interval->format('%d'));
    }

    /**
     * @throws NotAcceptableHttpException
     * @throws \yii\db\Exception
     */
    public static function decrementDay()
    {
        /**
         * @var $accounts BillAccount[]
         */
        $accounts = BillAccount::find()->where(['id'=>110])->all();
        //$now = (new Query())->select('NOW() as d')->one()['d'];
        $now = '2017-11-08 00:40:00';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            foreach ($accounts as $account) {
                echo "acc id: ".$account->id."\n";
                $diff = static::diffDays($account->processed_at, $now);
                echo "diff: $diff\n";
                $resDays = $account->days - $diff;
                echo "resDays: $resDays\n";
                if ($resDays <= 0) {
                    $account->days = 0;
                    $account->user->can_work = 0;
                    $account->processed_at = $now;
                } else {
                    if($account->days != $resDays) {
                        $account->days = $resDays;
                        $account->processed_at = $now;
                    }
                    $account->user->can_work = 1;
                }

                if (!$account->user->save()) {
                    throw new Exception('Ошибка сохранения пользователя');
                }
                if (!$account->save()) {
                    throw new Exception('Ошибка сохранения аккаунтинга');
                }
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new Exception($e->getMessage());
        }
    }
}

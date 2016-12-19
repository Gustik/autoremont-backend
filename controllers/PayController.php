<?php

namespace app\controllers;

use app\models\BillAccount;
use app\models\BillPayment;
use app\models\BillTariff;
use app\models\Page;
use app\models\User;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class PayController extends Controller
{
    public function behaviors()
    {
        return parent::behaviors();
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $tariffs = [];
        /**
         * @var BillTariff $tariff
         */
        foreach(BillTariff::find()->where(['city_id' => 1])->all() as $tariff) {
            $tariffs[$tariff->start_days] = $tariff->day_cost;
        }
        return $this->render('index', ['tariffs' => $tariffs]);
    }

    public function actionExecute($phone, $days)
    {
        $user = User::findIdentityByLogin($phone);
        if(!$user) {
            throw new NotFoundHttpException('Пользователь с таким номером не найден');
        }

        $model = new BillPayment();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $tariff = BillTariff::findTariffByDaysCount($days);

            if(!$tariff) {
                throw new Exception('Ошибка подбора тарифа');
            }

            $model->user_id = $user->id;
            $model->tariff_id = $tariff->id;
            $model->days = $days;

            if (!$model->validate()) {
                $transaction->rollback();

                throw new Exception($model->errors);
            }

            // Расчитываем сумму в зависимости от тарифа
            $model->amount = (int) ($model->days * BillTariff::findOne($model->tariff_id)->day_cost);
            $model->status = BillPayment::STATUS_PENDING;

            if (!$model->save()) {
                throw new Exception('Ошибка создания платежа');
            }

            $transaction->commit();
            // redirect to robokassa
            return "OK\n";
        } catch (Exception $e) {
            $transaction->rollback();
            throw new NotAcceptableHttpException($e->getMessage());
        }
    }

    /**
     * Сюда обращается робокасса в случае проведения платежа
     * @return string
     */
    public function actionResult()
    {
        // регистрационная информация (пароль #2)
        // registration info (password #2)
        $mrh_pass2 = "GqaVFEr2vgqLoVb22s71";

        //установка текущего времени
        //current date
        $tm=getdate(time()+9*3600);
        $date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

        // чтение параметров
        // read parameters
        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $shp_item = $_REQUEST["Shp_item"];
        $crc = $_REQUEST["SignatureValue"];

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

        // проверка корректности подписи
        // check signature
        if ($my_crc !=$crc)
        {
            return "bad sign\n";
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $payment = BillPayment::findOne($inv_id);
            if($payment) {
                throw new Exception("Платеж не найден");
            }
            $account = BillAccount::find()->where(['=', 'user_id', $payment->user_id])->one();

            if (!$account) {
                $account = new BillAccount();
                $account->user_id = $payment->user_id;
            }

            // Увеличиваем количество дней, в течении которого пользователь может работать
            $account->days += $payment->days;

            if (!$account->save()) {
                throw new Exception('Ошибка обновления аккаунтинга');
            }

            $user = $payment->user;

            // Если пользователь был отключен ранее, то включаем возможность работать
            if (!$user->can_work) {
                $user->can_work = true;
                if (!$user->save()) {
                    throw new Exception('Ошибка активации аккаунта');
                }
            }

            $payment->status = BillPayment::STATUS_OK;
            if (!$payment->save()) {
                throw new Exception('Ошибка обновления статуса платежа');
            }
            $transaction->commit();

            // признак успешно проведенной операции
            // success
            return "OK$inv_id\n";
        } catch (Exception $e) {
            $transaction->rollback();
            return $e->getMessage();
        }
    }

    /**
     * На него будет перенаправлен покупатель после успешного платежа.
     * @return string
     */
    public function actionSuccess()
    {
        // регистрационная информация (пароль #1)
        // registration info (password #1)
        $mrh_pass1 = "Qs0pZmA9zqvvE453WYSY";

        // чтение параметров
        // read parameters
        $out_summ = $_REQUEST["OutSum"];
        $inv_id = $_REQUEST["InvId"];
        $shp_item = $_REQUEST["Shp_item"];
        $crc = $_REQUEST["SignatureValue"];

        $crc = strtoupper($crc);

        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

        // проверка корректности подписи
        // check signature
        if ($my_crc != $crc)
        {
            return "bad sign\n";
        }

        // проверка наличия номера счета в истории операций
        // check of number of the order info in history of operations
        $payment = BillPayment::findOne($inv_id);

        if(!$payment) {
            return "error: payment not found";
        }

        if($payment->status == BillPayment::STATUS_OK) {
            return "Операция прошла успешно\n";
        }

        return "error";
    }

    /**
     * На него будет перенаправлен покупатель после неуспешного платежа, отказа от оплаты.
     */
    public function actionFail()
    {
        $inv_id = $_REQUEST["InvId"];
        echo "Вы отказались от оплаты. Заказ# $inv_id\n";
        echo "You have refused payment. Order# $inv_id\n";
        return;
    }
}

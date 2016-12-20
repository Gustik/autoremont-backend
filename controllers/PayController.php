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

            $mrh_login = "avtoremont";
            $mrh_pass1 = "avtoremont1982";
            $inv_id = $model->id;
            $inv_desc = "Оплата подписки";
            $out_summ = $model->amount;
            $IsTest = 1;
            $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

            $params = "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Description=$inv_desc&SignatureValue=$crc&IsTest=$IsTest";
            $url = "https://auth.robokassa.ru/Merchant/PaymentForm/FormMS.js?$params";

            return $this->render('submit', ['url' => $url, 'amount' => $out_summ]);

        } catch (Exception $e) {
            $transaction->rollback();
            throw new NotAcceptableHttpException($e->getMessage());
        }
    }

    /**
     * Сюда обращается робокасса в случае проведения платежа
     * @param $OutSum
     * @param $InvId
     * @param $SignatureValue
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionResult($OutSum, $InvId, $SignatureValue)
    {
        // регистрационная информация (пароль #2)
        // registration info (password #2)
        //$password2 = "GqaVFEr2vgqLoVb22s71";
        $password2 = "avtoremont1982";

        $crc = strtoupper($SignatureValue);
        $my_crc = strtoupper(md5("$OutSum:$InvId:$password2"));

        if ($my_crc != $crc) {
            return "bad sign\n";
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $payment = BillPayment::findOne($InvId);
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
            return "OK$InvId\n";

        } catch (Exception $e) {
            $transaction->rollback();
            return $e->getMessage();
        }
    }

    /**
     * На него будет перенаправлен покупатель после успешного платежа.
     * @param $OutSum
     * @param $InvId
     * @param $SignatureValue
     * @return string
     */
    public function actionSuccess($OutSum, $InvId, $SignatureValue)
    {
        // регистрационная информация (пароль #1)
        // registration info (password #1)
        //$password1 = "Qs0pZmA9zqvvE453WYSY";
        $password1 = "avtoremont1982";

        $crc = strtoupper($SignatureValue);
        $my_crc = strtoupper(md5("$OutSum:$InvId:$password1"));

        if ($my_crc != $crc) {
            return "bad sign\n";
        }

        $payment = BillPayment::findOne($InvId);

        if(!$payment) {
            return $this->render('error', ['message' => 'Платеж не найден']);
        }

        if($payment->status == BillPayment::STATUS_OK) {
            return $this->render('success', ['payment' => $payment]);
        }

        return $this->render('error', ['message' => 'Не корректный статус']);
    }

    /**
     * На него будет перенаправлен покупатель после неуспешного платежа, отказа от оплаты.
     * @param $InvId
     * @return string
     */
    public function actionFail($InvId)
    {
        $msg = "Вы отказались от оплаты.<br>Заказ# $InvId";
        return $this->render('error', ['message' => $msg]);
    }
}

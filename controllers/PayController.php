<?php

namespace app\controllers;

use app\models\Page;
use Yii;
use yii\web\Controller;

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
        return $this->render('index');
    }

    public function actionExecute($phone, $tariff, $year, $month, $day)
    {

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

        // признак успешно проведенной операции
        // success
        return "OK$inv_id\n";

        // запись в файл информации о проведенной операции
        // save order info to file
        /*$f=@fopen("order.txt","a+") or
        die("error");
        fputs($f,"order_num :$inv_id;Summ :$out_summ;Date :$date\n");
        fclose($f);*/
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
        /*$f=@fopen("order.txt","r+") or die("error");

        while(!feof($f))
        {
            $str=fgets($f);

            $str_exp = explode(";", $str);
            if ($str_exp[0]=="order_num :$inv_id")
            {
                echo "Операция прошла успешно\n";
                echo "Operation of payment is successfully completed\n";
            }
        }
        fclose($f);*/
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

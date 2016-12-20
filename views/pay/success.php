<?php

/* @var $payment \app\models\BillPayment */
/* @var $this yii\web\View */

$this->title = 'Платеж успешен';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-pay">
    <div class="row">
        <div class="col-md-3 col-md-offset-5">
            <div>
                <div id="pay-sum">
                    Номер заказа: <?= $payment->id ?><br>
                    Платеж успешно совершен
                </div>
            </div>
        </div>
    </div>
</div>
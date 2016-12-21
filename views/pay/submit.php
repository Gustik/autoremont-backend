<?php

/* @var $url string */
/* @var $amount string */
/* @var $phone string */
/* @var $days int */
/* @var $this yii\web\View */

$this->title = 'Подтверждение платежа';
$this->params['breadcrumbs'][] = ['label' => 'Оплата подписки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="site-pay">
    <div class="row">
        <div class="col-md-3 col-md-offset-5">

            <div id="pay-info" class="alert alert-info">
                Оплата подписки
            </div>

            <div>
                <div class="pay-sum-label">Телефон:</div>
                <div id="pay-sum">
                    <?=$phone?>
                </div>
                <div class="pay-sum-label">Количество дней:</div>
                <div id="pay-sum">
                    <?=$days?>
                </div>
                <div class="pay-sum-label">Сумма к оплате:</div>
                <div id="pay-sum">
                    <?=$amount?> руб.
                </div>
            </div>

            <div class="center-block">
                <script language=JavaScript src="<?=$url?>">
                </script>
            </div>
        </div>

    </div>
</div>

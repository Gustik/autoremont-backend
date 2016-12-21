<?php

/* @var $message string */
/* @var $this yii\web\View */

$this->title = 'Отмена платежа';
$this->params['breadcrumbs'][] = ['label' => 'Оплата подписки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-pay">
    <div class="row">
        <div class="col-md-3 col-md-offset-5">
            <div>
                <div id="pay-sum">
                    <?=$message?>
                </div>
            </div>
        </div>
    </div>
</div>
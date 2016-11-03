<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BillTariff */

$this->title = 'Update Bill Tariff: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Платежи', 'url' => ['bill-payment/index']];
$this->params['breadcrumbs'][] = ['label' => 'Тарифы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bill-tariff-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

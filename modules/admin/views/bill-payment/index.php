<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BillPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Платежи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Создать платеж', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Тарифы', ['bill-tariff/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Аккаунтинг', ['bill-account/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'created_at',
            'user.profile.name',
            'user.login',
            'user_id',
            'amount',
            'days',
            'tariff.name',
        ],
    ]); ?>
</div>

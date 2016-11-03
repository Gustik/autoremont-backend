<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\BillAccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аккаунтинг';
$this->params['breadcrumbs'][] = ['label' => 'Платежи', 'url' => ['bill-payment/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'user.login',
            'user_id',
            'days',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\DiscountCompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Discount Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Discount Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'company.name',
            'discount',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

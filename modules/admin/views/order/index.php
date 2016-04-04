<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'created_at',
            'updated_at',
            'description:ntext',
            'price',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {ban} {unban}',
                'buttons' => [
                    'ban' => function ($url, $model) {
                        return ( !$model->author->banned_to ? Html::a('<span class="glyphicon glyphicon-volume-off"></span>', $url, [
                            'title' => 'Ban',
                        ]) : '');
                    },
                    'unban' => function ($url, $model) {
                        return ( $model->author->banned_to ? Html::a('<span class="glyphicon glyphicon-volume-up"></span>', $url, [
                            'title' => 'Unban',
                        ]) : '');
                    }
                ],
            ],
        ],
    ]); ?>

</div>

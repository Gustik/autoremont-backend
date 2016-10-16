<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить заказ', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Теги', ['order-tag/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Статистика исполнителей', ['mech-order-stat/index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'created_at',
            'description:ntext',
            [
                'attribute' => 'name',
                'label' => 'Автор',
                'format' => 'html',
                'value' => function($model) {
                    return Html::a($model->author->profile->name,
                        ['user/view', 'id' => $model->author_id]);
                }
            ],
            [
                'label' => 'Предложения',
                'value' => function($model) {
                    return "{$model->offersCount}/{$model->autoOffersCount}";
                }
            ],
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'filter' => [1 => 'Ремонт', 2 => 'Запчасти'],
                'value' => function($model) {
                    return $model->category->name;
                }
            ],
            'tagNames',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {restore} {ban} {unban}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return ( $model->is_active ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Delete',
                            'data-confirm' => 'Вы уверены, что хотите удалить данный заказ?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '');
                    },
                    'restore' => function ($url, $model) {
                        return ( !$model->is_active ? Html::a('<span class="glyphicon glyphicon-refresh"></span>', $url, [
                            'title' => 'Restore',
                        ]) : '');
                    },
                    'ban' => function ($url, $model) {
                        return ( !$model->author->banned_to ? Html::a('<span class="text-danger glyphicon glyphicon-volume-off"></span>', $url, [
                            'title' => 'Ban',
                            'data-confirm' => 'Вы уверены, что хотите забанить данного пользователя?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : '');
                    },
                    'unban' => function ($url, $model) {
                        return ( $model->author->banned_to ? Html::a('<span class="text-success glyphicon glyphicon-volume-up"></span>', $url, [
                            'title' => 'Unban',
                        ]) : '');
                    }
                ],
            ],
        ],
    ]); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            'profile.name',
            'profile.birth_date:date',
            [
                'attribute' => 'isOnline',
                'format' => 'raw',
                'value' => $model->getFriendly('isOnline', 'boolean')
            ],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => $model->getFriendly('is_active', 'boolean')
            ],
            [
                'attribute' => 'is_admin',
                'format' => 'raw',
                'value' => $model->getFriendly('is_admin', 'boolean')
            ],
            'visited_at:datetime',
        ],
    ]) ?>

    <?= Collapse::widget([
        'items' => [
            [
                'label' => 'Дополнительная информация',
                'content' => DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'created_at:datetime',
                        'updated_at:datetime',
                        'password_hash',
                        'access_token',
                        'sms_code',
                        'profile.gcm_id'
                    ],
                ]),
            ],
            [
                'label' => 'Информация об автомобиле',
                'content' => DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'profile.car_brand',
                        'profile.car_model',
                        'profile.car_color',
                        'profile.car_year',
                    ],
                ]),
            ],
        ]
    ]); ?>

</div>

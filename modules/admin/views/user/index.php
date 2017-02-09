<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Новый пользователь', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'value' => 'profile.name',
            ],
            'login',
            [
                'attribute' => 'can_work',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getFriendly('can_work', 'boolean');
                },
                'contentOptions' => [
                    'align' => 'center',
                ],
            ],
            [
                'attribute' => 'isOnline',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getFriendly('isOnline', 'boolean');
                },
                'contentOptions' => [
                    'align' => 'center',
                ],
                'filter' => Html::activeDropDownList($searchModel, 'is_online',
                    [
                        '1' => 'Да',
                        '0' => 'Нет',
                    ],
                    [
                        'class' => 'form-control',
                        'prompt' => '',
                    ]),
            ],
            [
                'attribute' => 'rating',
                'value' => 'rating',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {send}',
                'buttons' => [
                    'send' => function ($url, $model, $key) {
                        return Html::a(
                            Html::tag('i', '', ['class' => 'glyphicon glyphicon-send']),
                            ['/admin/push/send', 'login' => $model->login],
                            ['data-method' => 'POST']
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
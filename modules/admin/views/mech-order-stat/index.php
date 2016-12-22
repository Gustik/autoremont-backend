<?php

use app\models\City;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\MechOrderStatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика исполнителей';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mech-order-stat-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'login',
            'name',
            'orders_count',
            'first_action',
            'last_action',
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'filter' => [1 => 'Ремонт', 2 => 'Запчасти'],
                'value' => function ($model) {
                    $cat[1] = 'Ремонт';
                    $cat[2] = 'Запчасти';

                    return $cat[$model->category_id];
                },
            ],
            [
                'attribute' => 'city_id',
                'value' => 'city.name',
                'filter' => ArrayHelper::map(City::find()->all(), 'id', 'name'),
            ],
        ],
    ]); ?>
</div>

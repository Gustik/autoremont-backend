<?php

use app\models\Company;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\StatCallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика по звонкам в компании';
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['company/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="stat-call-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'created_at',
                'format'=>['date', 'php:Y-m-d h:i'],
                'filter'=> DateRangePicker::widget([
                    'name' => 'date_range',
                    'convertFormat'=>true,
                    'startAttribute'=>'StatCallSearch[date_from]',
                    'endAttribute'=>'StatCallSearch[date_to]',
                    'startInputOptions' => ['value' => $searchModel->date_from],
                    'endInputOptions' => ['value' => $searchModel->date_to],
                    'pluginOptions'=>[
                        'timePicker'=>false,
                        'timePickerIncrement'=>30,
                        'locale'=>[
                            'format'=>'Y-m-d'
                        ]
                    ]
                ])
            ],
            'from:ntext',
            [
                'attribute' => 'to',
                'filter' => ArrayHelper::map(Company::find()->all(),'phone','name')
            ],
            [
                'attribute' => 'cat',
                'filter' => Company::$categories
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>
</div>

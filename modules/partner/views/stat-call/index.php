<?php

use app\models\Company;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\partner\models\StatCallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика по звонкам в компании';
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['company/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="stat-call-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>
    <?php
    $pdfHeader = [
        'L' => [
            'content' => 'Отчетный период с '.$searchModel->date_from.' по '.$searchModel->date_to,
        ],
        'C' => [
            'content' => '',
        ],
        'R' => [
            'content' => 'Сформированно: '.date('Y-m-d, h:i'),
        ],
        'line' => true,
    ];

    $pdfFooter = [
        'L' => [
            'content' => '(c) ООО "УУС" http://autogear.top',
            'font-size' => 10,
            'color' => '#333333',
            'font-family' => 'arial',
          ],
          'line' => true,
        ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'exportConfig' => [
            GridView::PDF => [
                'filename' => 'call-report',
                'config' => [
                    'methods' => [
                        'SetHeader' => [
                            ['odd' => $pdfHeader, 'even' => $pdfHeader],
                        ],
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter],
                        ],
                    ],
                ],
            ],
            GridView::EXCEL => [
                'filename' => 'call-report',
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d h:i'],
                'filter' => DateRangePicker::widget([
                    'name' => 'date_range',
                    'convertFormat' => true,
                    'startAttribute' => 'StatCallSearch[date_from]',
                    'endAttribute' => 'StatCallSearch[date_to]',
                    'startInputOptions' => ['value' => $searchModel->date_from],
                    'endInputOptions' => ['value' => $searchModel->date_to],
                    'pluginOptions' => [
                        'timePicker' => false,
                        'timePickerIncrement' => 30,
                        'locale' => [
                            'format' => 'Y-m-d',
                        ],
                    ],
                ]),
            ],
            'from:ntext',
            [
                'attribute' => 'to',
                'value' => 'companyName',
                'filter' => ArrayHelper::map(Company::find()->all(), 'phone', 'name'),
            ],
            [
                'attribute' => 'cat',
                'value' => 'companyCategory',
                'filter' => Company::$categories,
            ],

        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
        ],
    ]); ?>
</div>

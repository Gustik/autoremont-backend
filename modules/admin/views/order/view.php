<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = "Заказ №{$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'created_at',
            'updated_at',
            'description:ntext',
            'price',
            [
                'label' => 'Автомобиль',
                'value' => "{$model->car_brand} {$model->car_model} {$model->car_color} {$model->car_year}"
            ],
            'author.profile.name:text:Автор',
            'executor.profile.name:text:Исполнитель',
            'is_active:boolean:Активен',
            'city.name:text:Город',
            'category.name:text:Категория',
        ],
    ]) ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="panel-title">
                Предложения
                <a style="float: right;" href="<?= Url::to(['/admin/offer/create', 'id' => $model->id]) ?>" role="button" class="btn btn-warning btn-xs glyphicon glyphicon-plus"></a>
            </span>
        </div>
        <table class="panel-body table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        Время
                    </th>
                    <th>
                        Автор
                    </th>
                    <th>
                        Телефон
                    </th>
                    <th>
                        Текст
                    </th>
                </tr>
            </thead>
            <?php foreach ($model->offers as $offer) {
                echo $this->render('_offer', ['model' => $offer]);
            } ?>
        </table>
    </div>

</div>

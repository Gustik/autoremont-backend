<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderTag */

$this->title = 'Update Order Tag: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['order/index']];
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

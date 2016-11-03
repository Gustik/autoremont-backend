<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderTag */

$this->title = 'Добавление нового тега';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['order/index']];
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

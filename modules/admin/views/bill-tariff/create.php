<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BillTariff */

$this->title = 'Create Bill Tariff';
$this->params['breadcrumbs'][] = ['label' => 'Bill Tariffs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-tariff-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

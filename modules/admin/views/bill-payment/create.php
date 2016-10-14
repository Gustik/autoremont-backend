<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BillPayment */

$this->title = 'Create Bill Payment';
$this->params['breadcrumbs'][] = ['label' => 'Bill Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

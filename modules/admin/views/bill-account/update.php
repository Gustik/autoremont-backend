<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BillAccount */

$this->title = 'Update Bill Account: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bill Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bill-account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

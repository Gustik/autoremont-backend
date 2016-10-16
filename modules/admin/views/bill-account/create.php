<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\BillAccount */

$this->title = 'Create Bill Account';
$this->params['breadcrumbs'][] = ['label' => 'Bill Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-account-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

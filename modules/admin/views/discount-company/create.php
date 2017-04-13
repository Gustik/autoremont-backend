<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DiscountCompany */

$this->title = 'Create Discount Company';
$this->params['breadcrumbs'][] = ['label' => 'Discount Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

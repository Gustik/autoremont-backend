<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\MechOrderStatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mech-order-stat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'login') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'birth_date') ?>

    <?= $form->field($model, 'orders_count') ?>

    <?= $form->field($model, 'category_id') ?>

    <?php // echo $form->field($model, 'first_action')?>

    <?php // echo $form->field($model, 'last_action')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

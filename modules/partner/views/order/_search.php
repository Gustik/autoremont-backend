<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\partner\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'car_brand')?>

    <?php // echo $form->field($model, 'car_model')?>

    <?php // echo $form->field($model, 'car_year')?>

    <?php // echo $form->field($model, 'car_color')?>

    <?php // echo $form->field($model, 'author_id')?>

    <?php // echo $form->field($model, 'executor_id')?>

    <?php // echo $form->field($model, 'is_active')?>

    <?php // echo $form->field($model, 'city_id')?>

    <?php // echo $form->field($model, 'category_id')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

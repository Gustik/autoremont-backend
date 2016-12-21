<?php

use app\models\City;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BillTariff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bill-tariff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'day_cost')->textInput() ?>

    <?= $form->field($model, 'start_days')->textInput() ?>

    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(City::find()->all(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

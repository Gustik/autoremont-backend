<?php

use app\models\BillTariff;
use app\modules\admin\assets\CreatePaymentAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BillPayment */
/* @var $form yii\widgets\ActiveForm */
CreatePaymentAsset::register($this);
?>

<div class="bill-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'tariff_id')->dropDownList(ArrayHelper::map(BillTariff::find()->all(), 'id', 'name')); ?>

    <?= $form->field($model, 'days')->fileInput(['type' => 'number']) ?>


    <div>Сумма: <b id="amount"></b></div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    var tariffs = <?= json_encode(BillTariff::find()->asArray()->all()) ?>
</script>
<?php
use app\modules\discount\assets\ConsoleAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Ввод скидочного кода';
ConsoleAsset::register($this);
?>
<br><br><br><br><br><br><br>
<div class="main-console">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <?php $form = ActiveForm::begin(); ?>

            <?php echo $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
            <h4>Введите пин-код</h4>
            <?= $form->field($model, 'code')->label('') ?>
            <?= $form->field($model, 'params')->textArea(['rows' => 6])->hiddenInput()->label('') ?>
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'push-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
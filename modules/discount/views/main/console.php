<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Ввод скидочного кода';
?>
<div class="main-console">
    <?php $form = ActiveForm::begin(['id' => 'console-form']); ?>

		<?php echo $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
        <?= $form->field($model, 'code') ?>
        <?= $form->field($model, 'params')->textArea(['rows' => 6]) ?>
        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'push-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
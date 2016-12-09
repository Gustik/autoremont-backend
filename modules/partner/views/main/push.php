<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

$this->title = 'Push-уведомление';
?>
<div class="main-push">
    <?php $form = ActiveForm::begin([
        'id' => 'push-form',
    ]); ?>

		<?php echo $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'message')->textArea(['rows' => 6]) ?>
        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'push-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php 
    if (Yii::$app->session->hasFlash('pushFormSubmitted')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => 'Сообщение успешно отправлено.',
        ]);
    }
    ?>

    <?php 
    if (Yii::$app->session->hasFlash('pushSendFail')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger',
            ],
            'body' => 'Ошибка отправки сообщения.',
        ]);
    }
    ?>
</div>
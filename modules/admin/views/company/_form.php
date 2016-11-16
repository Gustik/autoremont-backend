<?php

use bupy7\cropbox\Cropbox;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'category')->dropDownList(Company::$categories, ['class' => 'form-control']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className()) ?>

    <?= $form->field($model, 'logo_image')->widget(Cropbox::className(), [
        'attributeCropInfo' => 'crop_info',
        'originalImageUrl' => Yii::getAlias('@web/img/upload/companies/').$model->logo,
        'pluginOptions' => [
            'variants' => [
                [
                    'width' => Company::LOGO_WIDTH,
                    'height' => Company::LOGO_HEIGHT
                ]
            ]
        ]
    ]) ?>


    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

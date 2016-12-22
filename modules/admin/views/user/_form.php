<?php

use app\models\City;
use bupy7\cropbox\Cropbox;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profile app\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary([$user, $profile]); ?>

    <?= $form->field($user, 'login')->textInput(['maxlength' => true, 'name' => 'User[login]']) ?>

    <?= $form->field($user, 'password')->hint($user->isNewRecord ? '' : 'Если необходимо сменить пароль - введите новый в поле сверху. В противном случае оставьте поле пустым.')->textInput(['maxlength' => true, 'name' => 'User[password]']) ?>

    <?= $form->field($user, 'is_admin')->dropDownList([0 => 'Нет', 1 => 'Да'], ['name' => 'User[is_admin]']) ?>

    <?= $form->field($user, 'is_partner')->dropDownList([0 => 'Нет', 1 => 'Да'], ['name' => 'User[is_partner]']) ?>

    <hr>
    <?= $form->field($profile, 'city_id')->dropDownList(ArrayHelper::map(City::find()->all(), 'id', 'name'), ['name' => 'Profile[city_id]']) ?>
    <?= $form->field($profile, 'name')->textInput(['maxlength' => true, 'name' => 'Profile[name]']) ?>
    <?= $form->field($profile, 'company_name')->textInput(['maxlength' => true, 'name' => 'Profile[company_name]']) ?>
    <?= $form->field($profile, 'company_address')->textInput(['maxlength' => true, 'name' => 'Profile[company_address]']) ?>
    <?= $form->field($profile, 'company_logo_image')->widget(Cropbox::className(), [
        'attributeCropInfo' => 'crop_info',
        'originalImageUrl' => Yii::getAlias('@web/img/upload/company-logo/').$profile->company_logo,
        'pluginOptions' => [
            'variants' => [
                [
                    'width' => 800,
                    'height' => 250,
                ],
            ],
        ],
    ]) ?>
    <hr>



    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a('Отмена', $user->isNewRecord ? ['/admin/user/index'] : ['/admin/user/view', 'id' => $user->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

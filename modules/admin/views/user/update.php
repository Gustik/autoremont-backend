<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profile app\models\Profile */

$this->title = "Редактирование пользователя: $user->login";
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->login, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'user' => $user,
        'profile' => $profile,
    ]) ?>

</div>

<?php

use app\modules\discount\assets\SuccessAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user \app\models\User */

$this->title = 'Ввод скидочного кода';
SuccessAsset::register($this);

?>
<div class="main-console">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <H2>Код успешно активирован!</H2>
            <h2><?=$user->login?></h2>
            <a id="back-btn" href="<?= Url::to(['main/console']) ?>" class="btn btn-lg btn-primary" role="button">Вернуться</a>
        </div>
    </div>

</div>

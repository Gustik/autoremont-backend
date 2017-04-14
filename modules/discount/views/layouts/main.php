<?php
use app\modules\discount\assets\DiscountAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;


/* @var $this \yii\web\View */
/* @var $content string */

DiscountAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        if (!Yii::$app->user->isGuest) {
            NavBar::begin([
                'brandLabel' => 'Консоль',
                'brandUrl' => ['/discount'],
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => 'Статистика', 'url' => ['main/index']],
                    ['label' => 'Ввод кода', 'url' => ['main/console']],
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Выход', 'url' => ['main/logout'],
                        'linkOptions' => ['data-method' => 'post'], ],
                ],
            ]);
            NavBar::end();
        }
        ?>
        <br><br><br><br><br>
        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => ['/discount']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">

    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

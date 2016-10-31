<?php
use app\modules\admin\assets\AdminAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AdminAsset::register($this);
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
                    'brandLabel' => 'Админ-панель',
                    'brandUrl' => ['/admin'],
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav'],
                    'items' => [
                        ['label' => 'Пользователи', 'url' => ['user/index']],
                        ['label' => 'Заказы', 'url' => ['order/index']],
                        ['label' => 'Компании', 'url' => ['company/index']],
                        ['label' => 'Биллинг', 'url' => ['bill-payment/index']],
                        ['label' => 'Страницы', 'url' => ['page/index']],
                        ['label' => 'Push', 'url' => ['main/push']]
                    ],
                ]);
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => [
                        ['label' => 'Выход', 'url' => ['main/logout'],
                            'linkOptions' => ['data-method' => 'post']]
                    ],
                ]);
                NavBar::end();
            }
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => ['/admin']],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-right">&copy; ООО УУС 2015 - <?= date('Y') ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

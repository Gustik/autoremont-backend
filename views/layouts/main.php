<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Авторемонт</title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Авторемонт',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Главная', 'url' => ['/']],
                    ['label' => 'Лицензионное соглашение', 'url' => ['/license']],
                ],
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <br>
            <br>
            <br>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-right">
                &copy; ООО УУС 2015 - <?= date('Y') ?><br><br>
                <span class="small muted">
                <b>ИНН:</b> 1435305962<br>
                <b>Телефон:</b> +7(914) 2 88-88-77<br>
                <b>Email:</b> info@bbgroup.pro<br>
                <b>Юр. адрес:</b> 677014, Республика Саха(Якутия),<br>
                г. Якутск, ул. Н.Е. Мординова, д.32, к. 1, кв.29</span>
            </p>

        </div>
    </footer>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-27359326-9', 'auto');
        ga('send', 'pageview');
    </script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
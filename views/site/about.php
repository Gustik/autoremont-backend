<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'О приложении';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2>
		<blockquote style = "color: #54cc61">Как клиент</blockquote>
	</h2>
    которому нужна помощь по ремонту, вы описываете проблему и назначаете цену, которую готовы платить.

    <h2>
		<blockquote style = "color: #54cc61">Как мастер</blockquote>
	</h2>
	вы можете выбрать любой свободный заказ, позвонить клиенту и обговорить детали. Если вы договоритесь, заказ перейдет к вам.
</div>

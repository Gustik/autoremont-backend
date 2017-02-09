<?php
/**
 * @var int
 * @var $userCount int
 */
$this->title = 'Личный кабинет';
?>
<div>
	<div class="panel panel-primary">
  		<div class="panel-heading">Общая статистика (<?=Yii::$app->user->identity->profile->city->name?>)</div>
		<div class="panel-body">
			Количество пользователей: <?=$userCount?><br>
			Количество заказов: <?=$orderCount?>
		</div>
	</div>
</div>

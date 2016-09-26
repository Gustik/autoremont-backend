<?php

use app\models\Stat;
use yii\bootstrap\Html;
use dosamigos\chartjs\ChartJs;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\multiselect\MultiSelect;

$this->title = 'Админ-панель';
?>
<div>
	<div class="panel panel-primary">
  		<div class="panel-heading">Общая статистика</div>
		<div class="panel-body">
			<!--<div class="row">
				<div class="col-sm-4">
					<label>Дата</label>
					<?= DateRangePicker::widget([
					    'name' => 'from',
					    'value' => $from,
					    'nameTo' => 'to',
					    'valueTo' => $to,
					    'labelTo' => '&nbsp;-&nbsp;',
					    'language' => 'ru',
					    'clientOptions' => [
					    	'autoclose' => true,
					    	'format' => 'yyyy-mm-dd'
					    ]
					]); ?>
				</div>
				<div class="col-sm-7">
					<label>Поля</label><br>
			        <?= MultiSelect::widget([
			            'options' => ['multiple' => 'multiple'],
			            'data' => [
				            'user_total' => 'Всего (пользователи)',
				            'user_active' => 'Активные (пользователи)',
				            'user_new' => 'Новые (пользователи)',
				            'order_total' => 'Всего (заказы)',
				            'order_new' => 'Новые (заказы)'
			            ],
			            'value' => $datasets,
			            'name' => 'datasets',
			            'clientOptions' => [
			                'includeSelectAllOption' => true,
			                'selectAllText' => 'Выбрать все',
			                'nonSelectedText' => 'Не выбрано',
			                'nSelectedText' => 'Выбрано',
			                'numberDisplayed' => 2,
			                'maxHeight' => 200
			            ],
			        ]) ?>
				</div>
			</div>
			<br>
			<button class="btn btn-default" onClick="alert('Пока не работает. Позже будет готово.');">Обновить</button>-->
			<div class="col-xs-6">
				Всего пользователей: <?= $userCount ?>
			</div>
			<div class="col-xs-6">
				<span class="<?=($smsBalance < 50)? 'text-danger' : 'text-success' ?>">
				Баланс СМС: <?= $smsBalance ?> рублей
					</span>
			</div>
			<br><br>
			<?= ChartJs::widget([
			    'type' => 'Line',
			    'options' => [
			        'height' => 400,
			        'width' => 860
			    ],
			    'clientOptions' => [
		    		'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>'
		    	],
			    'data' => $graphs
			]); ?>
			<hr>
			<?= ChartJs::widget([
			    'type' => 'Line',
			    'options' => [
			        'height' => 400,
			        'width' => 860
			    ],
			    'clientOptions' => [
		    		'multiTooltipTemplate' => '<%= datasetLabel %> - <%= value %>'
		    	],
			    'data' => $graphsTotal
			]); ?>

			<div class="col-xs-12">
				<h2>TOP <?=$top = 50?> Автомастеров</h2>
				<table class="table table-striped">
					<thead>
					<tr>
						<th>Телефон</th>
						<th width="300px">Имя</th>
						<th>Дата рождения</th>
						<th>Количество заказов</th>
						<th>Первая активность</th>
						<th>Последняя активность</th>
					</tr>
					</thead>
				<?php foreach(Stat::getTopMechs(1, $top) as $mech): ?>
					<tr>
						<td><?=$mech['login']?></td>
						<td><?=$mech['name']?></td>
						<td><?=$mech['birth_date']?></td>
						<td><?=$mech['c']?></td>
						<td><?=$mech['first_action']?></td>
						<td><?=$mech['last_action']?></td>
					</tr>
				<?php endforeach ?>
				</table>
			</div>
			<div class="col-xs-12">
				<h2>TOP <?=$top?> Автомагазинов</h2>
				<table class="table table-striped">
					<thead>
					<tr>
						<th>Телефон</th>
						<th width="300px">Имя</th>
						<th>Дата рождения</th>
						<th>Количество заказов</th>
						<th>Первая активность</th>
						<th>Последняя активность</th>
					</tr>
					</thead>
					<?php foreach(Stat::getTopMechs(2, $top) as $mech): ?>
						<tr>
							<td><?=$mech['login']?></td>
							<td><?=$mech['name']?></td>
							<td><?=$mech['birth_date']?></td>
							<td><?=$mech['c']?></td>
							<td><?=$mech['first_action']?></td>
							<td><?=$mech['last_action']?></td>
						</tr>
					<?php endforeach ?>
				</table>
			</div>
		</div>
	</div>
</div>

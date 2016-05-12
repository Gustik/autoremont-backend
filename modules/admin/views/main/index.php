<?php

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
			<div class="col-xs-12">
				Всего пользователей: <?= $userCount ?>
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
		</div>
	</div>
</div>

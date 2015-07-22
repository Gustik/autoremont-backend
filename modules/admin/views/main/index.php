<?php

use yii\helpers\Html;

$this->title = 'Админ-панель';
?>
<div>
	<div class="panel panel-primary">
  	<div class="panel-heading">Общая статистика</div>
		<div class="panel-body">
		<table class="table table-hover table-bordered">
			<tr>
				<th colspan="2">Пользователи</th>
			</tr>
			<?php
				foreach ($stat as $item) {
					echo Html::beginTag('tr');
						echo Html::tag('td', $item->label);
						echo Html::beginTag('td');
							echo ( $item->link ? Html::a($item->value, $item->link) : $item->value );
						echo Html::endTag('td');
					echo Html::endTag('tr');
				}
			?>
		</table>
		</div>
	</div>
</div>

<?php
/**
 * @var \app\models\DiscountUse[]
 * @var $userCount                int
 */
$this->title = 'Личный кабинет';
?>
<div>
	<div class="panel panel-primary">
  		<div class="panel-heading">Общая статистика</div>
		<div class="panel-body">
			<table>
				<tr><th width="200px">Телефон</th><th>Время</th></tr>
		<?php foreach ($du as $discount): ?>
				<tr>
					<td><?= $discount->user->login ?></td>
			 		<td><?= $discount->created_at ?> </td>
				</tr>
		<?php endforeach ?>
			</table>
		</div>
	</div>
</div>

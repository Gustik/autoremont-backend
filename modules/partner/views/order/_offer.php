<?php


/* @var $this yii\web\View */
/* @var $model app\models\Offer */
?>

<tr>
	<td>
		<?= $model->created_at ?>
	</td>
	<td>
		<?= $model->author->profile->name ?>
	</td>
	<td>
		<?= $model->author->login ?>
	</td>
	<td>
		<?= $model->text ?>
	</td>
</tr>
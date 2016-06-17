<?php

use yii\helpers\Html;    
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Offer */

$this->title = 'Новое предложение';
$this->params['breadcrumbs'][] = ['label' => 'Заказ', 'url' => ["/admin/order/view/$orderId"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="offer-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="offer-form">

	    <?php $form = ActiveForm::begin(); ?>

		<div class="row">
		    <div class="col-md-6">
		    	<label class="control-label">Номер</label>
		    	<input class="form-control" id="offer-create-phone">
		    </div>
		    <div class="col-md-6">
		    	<label class="control-label">Автор</label>
		    	<input class="form-control" id="offer-create-name" readonly>
		    </div>
		</div>
    	<?= $form->field($model, 'author_id')->hiddenInput()->label('') ?>
		<div class="row">
		    <div class="col-md-12">
		    	<?= $form->field($model, 'text')->textarea(['rows' => 6, 'value' => "Предложение создано автоматической системой поиска запчастей приложения Авторемонт.\nЦена: "]) ?>
		    </div>
		</div>

    	<?= $form->field($model, 'order_id')->hiddenInput(['value' => $orderId])->label('') ?>

	    <div class="form-group">
	        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>

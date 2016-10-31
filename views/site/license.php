<?php

/* @var $page \app\models\Page */
/* @var $this yii\web\View */
$this->title = $page->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-license">
	<?= $page->text ?>
</div>

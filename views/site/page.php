<?php

/* @var $page \app\models\Page */
/* @var $this yii\web\View */
$this->title = $page->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-page">
	<?= $page->text ?>
</div>

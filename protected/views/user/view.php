<?php
/* @var $this UserController */
/* @var $model User */
?>

<h1>Пользователь <?php echo $model->origin; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'origin',
		'rating',
		'role',
		'vk',
		'create_time',
		'last_visit',
	),
)); ?>

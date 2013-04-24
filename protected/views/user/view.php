<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список User', 'url'=>array('index')),
	array('label'=>'Создать User', 'url'=>array('create')),
	array('label'=>'Редактировать User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Удалить User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Управление User', 'url'=>array('admin')),
);
?>

<h1>Просмотр User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'email',
		'password',
		'origin',
		'rating',
		'role',
		'vk',
		'confirm',
		'avatar_url',
		'activ_key',
		'create_time',
		'last_visit',
	),
)); ?>

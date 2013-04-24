<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Список User', 'url'=>array('index')),
	array('label'=>'Создать User', 'url'=>array('create')),
	array('label'=>'Просмотр User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Управление User', 'url'=>array('admin')),
);
?>

<h1>Редактировать User <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
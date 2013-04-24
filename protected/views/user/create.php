<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Список User', 'url'=>array('index')),
	array('label'=>'Управление User', 'url'=>array('admin')),
);
?>

<h1>Создать User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
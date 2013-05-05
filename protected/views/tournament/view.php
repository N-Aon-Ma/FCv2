<?php
/* @var $this TournamentController */
/* @var $model Tournament */
?>

<h1>Просмотр Турнира #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'season',
		'create_date',
		'active',
		'type',
		'number_of_groups',
		'level',
		'number_of_rounds',
		'label',
		'rounds_of_semiseason',
		'end',
	),
)); ?>

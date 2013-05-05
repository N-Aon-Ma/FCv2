<?php
/* @var $this TournamentController */
/* @var $dataProvider CActiveDataProvider */

?>

<h1>Турниры</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

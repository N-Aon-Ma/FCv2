<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('origin')); ?>:</b>
	<?php echo CHtml::encode($data->origin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rating')); ?>:</b>
	<?php echo CHtml::encode($data->rating); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('role')); ?>:</b>
	<?php echo CHtml::encode($data->role); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vk')); ?>:</b>
	<?php echo CHtml::encode($data->vk); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('confirm')); ?>:</b>
	<?php echo CHtml::encode($data->confirm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avatar_url')); ?>:</b>
	<?php echo CHtml::encode($data->avatar_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('activ_key')); ?>:</b>
	<?php echo CHtml::encode($data->activ_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_visit')); ?>:</b>
	<?php echo CHtml::encode($data->last_visit); ?>
	<br />

	*/ ?>

</div>
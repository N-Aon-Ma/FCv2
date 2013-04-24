<?php
/**
 * This is the template for generating a form script file.
 * The following variables are available in this template:
 * - $this: the FormCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getModelClass(); ?>Controller */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<div class="form">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass).'-'.basename($this->viewName)."-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

	<p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными.</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php foreach($this->getModelAttributes() as $attribute): ?>
	<div class="row">
		<?php echo "<?php echo \$form->labelEx(\$model,'$attribute'); ?>\n"; ?>
		<?php echo "<?php echo \$form->textField(\$model,'$attribute'); ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'$attribute'); ?>\n"; ?>
	</div>

<?php endforeach; ?>

	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton('Принять'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->
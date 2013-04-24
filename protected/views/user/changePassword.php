<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

        <?php echo $form->hiddenField($model,'email', array('value'=>$email)); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'rePassword'); ?>
        <?php echo $form->passwordField($model,'rePassword',array('size'=>40,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'rePassword'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сменить пароль'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
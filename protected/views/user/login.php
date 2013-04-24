<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Вход';
?>

<h1>Вход</h1>

<p>Пожалуйста, заполните следующую форму с Вашими учетными данными для входа:</p>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'enableAjaxValidation'=>true
    )); ?>

    <p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными.</p>

    <div class="row">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email' ,array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password', array('size'=>40,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row rememberMe">
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'Запомнить меня'); ?>
        <?php echo $form->error($model,'rememberMe'); ?>
    </div>

    <div class="row">
        <?php echo CHtml::link('Забыли пароль?','recovery'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Вход'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

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
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
        ),
    )); ?>

    <p class="note">Поля, отмеченные <span class="required">*</span> являются обязательными.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?if(Yii::app()->user->isGuest):?>
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
            <?php echo $form->error($model,'email'); ?>
        <?endif?>
    </div>

    <div class="row">
        <?if(!Yii::app()->user->isGuest):?>
            <?php echo $form->labelEx($model,'oldPassword'); ?>
            <?php echo $form->passwordField($model,'oldPassword',array('size'=>40,'maxlength'=>64)); ?>
            <?php echo $form->error($model,'oldPassword'); ?>
        <?endif?>
    </div>

    <div class="row">
        <?php $model->password = null; ?>
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'rePassword'); ?>
        <?php echo $form->passwordField($model,'rePassword',array('size'=>40,'maxlength'=>64)); ?>
        <?php echo $form->error($model,'rePassword'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'origin'); ?>
        <?php echo $form->textField($model,'origin',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'origin'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'vk'); ?>
        <?php echo $form->textField($model,'vk',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'vk'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'avatar'); ?>
        <?php echo $form->fileField($model,'avatar'); ?>
        <?php echo $form->error($model,'avatar'); ?>
    </div>

    <div class="row">
        <?if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
            <?=CHtml::activeLabelEx($model, 'captcha')?>
            <?$this->widget('CCaptcha')?>
            <?=CHtml::activeTextField($model, 'captcha')?>
            <?php echo $form->error($model,'captcha'); ?>
        <?endif?>
    </div>

    <div class="row">
        <?php //echo $form->labelEx($model,'avatar'); ?>
        <?php //echo $form->fileField($model,'avatar'); ?>
        <?php //echo $form->error($model,'avatar'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Зарегистрироваться' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
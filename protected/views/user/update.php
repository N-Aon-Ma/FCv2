<?php
/* @var $this UserController */
/* @var $model User */
?>

<h1>Редактировать профиля игрока <?php echo $model->origin; ?></h1>

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
            <?php echo $form->labelEx($model,'oldPassword'); ?>
            <?php echo $form->passwordField($model,'oldPassword',array('size'=>40,'maxlength'=>64)); ?>
            <?php echo $form->error($model,'oldPassword'); ?>
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

    <div class="row buttons">
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
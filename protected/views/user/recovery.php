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
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
        <?if(CCaptcha::checkRequirements() && Yii::app()->user->isGuest):?>
            <?=CHtml::activeLabelEx($model, 'captcha')?>
            <?$this->widget('CCaptcha')?>
            <?=CHtml::activeTextField($model, 'captcha')?>
            <?php echo $form->error($model,'captcha'); ?>
        <?endif?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Восстановить пароль'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
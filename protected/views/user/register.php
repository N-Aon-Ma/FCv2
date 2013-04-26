<?php
/* @var $this UserController */
/* @var $model User */
?>

    <h1>Регистрация</h1>

    <div class="form">

    <?php $this->widget('application.extensions.TheCKEditor.theCKEditorWidget',array(
        'model'=>$model,                # Data-Model (form model)
        'attribute'=>'vk',         # Attribute in the Data-Model
        'height'=>'400px',
        'width'=>'100%',
        'toolbarSet'=>'Full',          # EXISTING(!) Toolbar (see: ckeditor.js)
        'ckeditor'=>Yii::app()->basePath.'/../ckeditor/ckeditor.php',
        # Path to ckeditor.php
        'ckBasePath'=>Yii::app()->baseUrl.'/ckeditor/',
        # Relative Path to the Editor (from Web-Root)
        'css' => Yii::app()->baseUrl.'/css/index.css',
        # Additional Parameters
    ) ); ?>



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
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('size'=>40,'maxlength'=>128)); ?>
            <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="row">
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
            <?=CHtml::activeLabelEx($model, 'captcha')?>
            <?$this->widget('CCaptcha')?>
            <?=CHtml::activeTextField($model, 'captcha')?>
            <?php echo $form->error($model,'captcha'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Зарегистрироваться'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
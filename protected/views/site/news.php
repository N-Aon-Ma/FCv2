<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

    <?php echo $model->head; ?>
    <?php echo $model->body; ?>
    <?php echo $model->date; ?>
    <?php echo $model->head_image_url; ?>
    <?php echo $model->author->origin; ?>

    <?php for ($i=0; $i<count($model->comment); $i++) : ?>
    <div class="row">
        <?php echo $model->comment[$i]['value']; ?>
        <?php echo $model->comment[$i]['user']->origin; ?>
    </div>
    <?php endfor ?>

    <?php if (!Yii::app()->user->isGuest): ?>
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'news-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'newComment'); ?>
        <?php echo $form->textField($model,'newComment'); ?>
        <?php echo $form->error($model,'newComment'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Добавить комментарий'); ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php endif ?>

    <?php if(Yii::app()->user->hasFlash('addComment')):
        echo Yii::app()->user->getFlash('addComment');
    endif; ?>

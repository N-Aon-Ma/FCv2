<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

    <?php echo $model->head; ?>
    <?php echo $model->body; ?>
    <?php echo $model->date; ?>
    <?php echo $model->head_image_url; ?>
    <?php echo $model->author->origin; ?>

<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Добро пожаловать на сайт <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Поздравляем! Вы успешно создали своё Yii-приложение.</p>

<p>Вы можете изменить содержимое этой страницы, изменив следующие два файла:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p> Для получения более подробной информации о том, как дальше развивать это приложение, пожалуйста, прочитайте
<a href="http://www.yiiframework.com/doc/">документацию</a>.
Не стесняйтесь спрашивать на <a href="http://www.yiiframework.com/forum/">форуме</a>,
если у вас возникли вопросы. </p>

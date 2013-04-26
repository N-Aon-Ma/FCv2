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
<?php $this->widget('application.extensions.TheCKEditor.theCKEditorWidget',array(
    'model'=>null,                # Data-Model (form model)
    'attribute'=>'content',         # Attribute in the Data-Model
    'height'=>'400px',
    'width'=>'100%',
    'toolbarSet'=>'Basic',          # EXISTING(!) Toolbar (see: ckeditor.js)
    'ckeditor'=>Yii::app()->basePath.'/../ckeditor/ckeditor.php',
                                    # Path to ckeditor.php
    'ckBasePath'=>Yii::app()->baseUrl.'/ckeditor/',
                                    # Relative Path to the Editor (from Web-Root)
    'css' => Yii::app()->baseUrl.'/css/index.css',
                                    # Additional Parameters
) ); ?>
<p> Для получения более подробной информации о том, как дальше развивать это приложение, пожалуйста, прочитайте
<a href="http://www.yiiframework.com/doc/">документацию</a>.
Не стесняйтесь спрашивать на <a href="http://www.yiiframework.com/forum/">форуме</a>,
если у вас возникли вопросы. </p>

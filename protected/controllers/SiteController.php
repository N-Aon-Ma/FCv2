<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('index', 'page', 'error'),
                'users'=>array('*'),
            ),
            array('allow',
                'actions'=>array('add'),
                'roles'=>array('moder'),
            ),
            array('allow',
                'actions' => array('edit'),
                'roles'=>array('moder'),
                'expression'=>"Yii::app()->controller->isAuthor()",
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if (isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $model=$this->loadModel($id);
            $model->scenario = 'comment';
            if (isset($_POST['News']['newComment'])){
                if ($model->addComment($_POST['News']['newComment'])){
                    Yii::app()->user->setFlash('addComment','Ваш комментарий добавлен');
                    //TODO - и здесь та же хурма с редиректом/рендером блин
                    $model->newComment = null;
                    $this->redirect(array('news','id'=>$model->id));
                } else {
                    Yii::app()->user->setFlash('addComment','Ошибка при добавлении комментария');
                    $this->redirect(array('news','id'=>$model->id));
                }
            } else{
                $this->render('news', array('model'=>$model));
            }
        } else {
            $model = new News();
            $news = $model->getLatestNews();
		    $this->render('index', array('news'=>$news));
        }
	}

    public function actionAdd(){
        $model = new News('add');
        if (isset($_POST['News'])){
            $model->attributes = $_POST['News'];
            if($model->addNews())
                $this->redirect(array('index','id'=>$model->id));
        }
        $this->render('add', array('model'=>$model));
    }

    public function actionEdit($id){
        $model = $this->loadModel($id);
        if (isset($_POST['News'])){
            $model->attributes = $_POST['News'];
            $model->addNews();
            $this->redirect(array('index','id'=>$model->id));
        }
        $this->render('add', array('model'=>$model));
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function isAuthor(){
        if (isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $model = self::loadModel($id);
            if ($model->author->id==Yii::app()->user->id){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function loadModel($id)
    {
        $model=News::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Неверный URL.');
        return $model;
    }

}
<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
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
                'actions'=>array('captcha', 'profile', 'index'),
                'users'=>array('*'),
            ),
            array('deny',
                'actions'=>array('register','login', 'recovery', 'activate'),
                'users'=>array('@'),
            ),
			array('allow',
				'actions'=>array('register','login', 'recovery', 'activate'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('logout', 'message', 'writeMessage'),
				'users'=>array('@'),
			),
            array('allow',
                'actions' => array('update'),
                'users'=>array('@'),
                'expression'=>'$user->id=='.(int)$_GET['id'],
            ),
			array('allow',
				'actions'=>array('manage'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionRegister(){
        $model = new User('register');
        $this->performAjaxValidation($model);
        if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->saveUser()){
                $this->render('message',array('title'=>'Регистрация','content'=>'На Ваш email-адрес отправлено письмо. Перейди по ссылке в нём для завершения регистрации.'));
            } else {
                $this->render('register',array('model'=>$model));
            }
		} else {
            $this->render('register',array('model'=>$model));
        }
    }

    public function actionActivate(){
        if (isset($_GET['email']) && isset($_GET['code'])){
            $model = new User();
            $this->render('message',array('title'=>'Активация аккаунта','content'=>$model->activate($_GET['email'],$_GET['code'])));
        } else {
            $this->render('message',array('title'=>'Активация аккаунта','content'=>'Неверный URL.'));
        }
    }

    public function actionLogin()
    {
        $model=new User('login');
        $this->performAjaxValidation($model);

        // collect user input data
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()){
                if (Yii::app()->user->returnUrl->action!=='login'){
                    $this->redirect(Yii::app()->user->returnUrl);
                } else{
                    $this->redirect(array('/site/index'));
                }
            }
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    public function actionRecovery(){
        $model = new User('recovery');
        $this->performAjaxValidation($model);
        if (isset($_POST['User']['email']) && !isset($_POST['User']['password'])){
            $this->render('message',array('title'=>'Восстановление пароля','content'=>$model->sendRecoveryMail($_POST['User']['email'])));
        } elseif(isset($_POST['User']['email']) && isset($_POST['User']['password'])) {
            if($model->changePassword($_POST['User']['email'], $_POST['User']['password'])){
                $this->render('message',array('title'=>'Восстановление пароля','content'=>'Вы успешно сменили пароль.'));
            } else {
                $this->render('message',array('title'=>'Восстановление пароля','content'=>'Ошибка смены пароля.'));
            }
        } elseif (isset($_GET['email']) && isset($_GET['code'])){
            if ($model->recovery($_GET['email'], $_GET['code'])){
                $this->render('changePassword', array('model'=>$model, 'email'=>$_GET['email']));
            } else {
                $this->render('message',array('title'=>'Восстановление пароля','content'=>'Неверный URL.'));
            }
        } else {
            $this->render('recovery',array('model'=>$model));
        }
    }

    public function actionLogout(){
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionProfile($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $this->performAjaxValidation($model);
		if(isset($_POST['User']))
		{
			if($model->updateUser($_POST['User']))
				$this->redirect(array('profile','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('manage',array(
			'model'=>$model,
		));
	}

    public function actionMessage(){
        if (isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $message = User::model()->getMessageAndRead($id);
            $this->render('readMessage', array('message'=>$message));
        } else {
            $messages = User::model()->getAllMessages();
            $this->render('messages', array('messages'=>$messages));
        }
    }

    public function actionWriteMessage($id){
        $model = new Message();
        $dest = $this->loadModel($id);
        if (isset($_POST['Message'])){
            $model->attributes = $_POST['Message'];
            if ($model->saveMessage($id)){
                $text = 'Ваше сообщение пользователю '.$dest->origin.' отправлено.';
            } else {
                $text = 'Ошибка при отправке сообщения';
            }
            //TODO - почему-то не рендерит...
            $this->render('message',array('title'=>'Отправка сообщения','content'=>$text));
        } else {
            $this->render('writeMessage', array('model'=>$model, 'dest'=>$dest->origin));
        }
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Неверный URL.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

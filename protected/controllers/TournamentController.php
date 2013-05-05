<?php

class TournamentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'roles'=>array('admin'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $model=new Tournament;
        $this->performAjaxValidation($model);
        $teams=new CActiveDataProvider('Team');
        $t = array(0=>'Выберите команду:');

        for ($i=0; $i<$teams->itemCount; $i++){
            $t[$teams->data[$i][id]]=$teams->data[$i][name];
        }

        if (isset($_GET['league'])){
            $model->scenario = 'createLeague';
            if(isset($_POST['Tournament']))
            {
                if($model->saveLeague($_POST['Tournament']))
                    $this->redirect(array('view','id'=>$model->id));
                else
                    $this->redirect(array('tournament/create/league'));
            }

            $data = $model->getDataForCreateLeague();

            $this->render('createLeague',array(
                'model'=>$model,
                'teams'=>$t,
                'data'=>$data,
            ));
        }
        elseif (isset($_GET['playoff'])){
            $model->scenario = 'createPlayOff';
            if(isset($_POST['Tournament']))
            {
                if($model->savePlayOff($_POST['Tournament']))
                    $this->redirect(array('view','id'=>$model->id));
                else
                    $this->redirect(array('tournament/create/playoff'));
            }

            $data = $model->getDataForCreatePlayOff();

            $this->render('createPlayOff',array(
                'model'=>$model,
                'teams'=>$t,
                'data'=>$data,
            ));
        } else
            throw new CHttpException(404, 'Неверный URL.');
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tournament');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tournament the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tournament::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tournament $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tournament-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

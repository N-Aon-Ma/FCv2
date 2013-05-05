<?php

class ConfigController extends Controller
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
				'actions'=>array('index'),
				'roles'=>array('admin'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionIndex()
    {
        // извлекаем элементы, которые будем обновлять в пакетном режиме,
        // предполагая, что каждый элемент является экземпляром класса модели 'Item'
        $items=Config::getItemsToUpdate();
        if(isset($_POST['Config']))
        {
            foreach($items as $i=>$item)
            {
                if(isset($_POST['Config'][$i])){
                    $item->attributes=$_POST['Config'][$i];
                    $item->save();
                }
            }
            $this->refresh();
        }
        // отображаем представление с формой для ввода табличных данных
        $this->render('index',array('items'=>$items));
    }

}

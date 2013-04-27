<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $origin
 * @property integer $rating
 * @property string $role
 * @property string $vk
 * @property integer $confirm
 * @property string $avatar_url
 * @property string $activ_key
 * @property string $create_time
 * @property string $last_visit
 */
class User extends CActiveRecord
{
    private $_identity;
    public $rememberMe;
    public $rePassword;
    public $avatar;
    public $captcha;
    public $oldPassword;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('email, password', 'required', 'on'=> 'register, login, recovery'),
            array('rePassword', 'required', 'on'=>'recovery'),
            array('origin, rePassword', 'required', 'on'=>'register'),
            array('captcha', 'captcha', 'on'=>'recovery, register'),
            array('email', 'email'),
            array('rePassword', 'compare', 'compareAttribute'=>'password', 'on'=>'register, recovery'),
            array('vk', 'url'),
            array('avatar', 'file', 'types'=>'jpg, jpeg, png, gif', 'safe'=>true, 'allowEmpty'=>true),
            array('email, origin', 'unique', 'on'=>'register, update'),
			array('email, origin, vk, avatar_url', 'length', 'max'=>128),
            array('password, origin', 'length', 'min'=>3),
			array('password, activ_key', 'length', 'max'=>256),
			array('role', 'length', 'max'=>5),
			array('last_visit, rememberMe', 'safe'),
            array('password, rePassword, oldPassword, origin, vk', 'length', 'max'=>64, 'allowEmpty' => true, 'on'=>'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, password, origin, rating, role, vk, confirm, avatar_url, activ_key, create_time, last_visit', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email-адрес',
			'password' => 'Пароль',
			'origin' => 'Origin ID',
			'rating' => 'Рейтинг',
			'role' => 'Роль',
			'vk' => 'Вконтакте',
			'create_time' => 'Время создания',
			'last_visit' => 'Последний визит',
            'rememberMe' => 'Запомнить меня',
            'rePassword' => 'Повторите пароль',
            'captcha' => 'Код подтверждения',
            'oldPassword' => 'Старый пароль',
            'avatar'=>'Аватар',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('origin',$this->origin,true);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('vk',$this->vk,true);
		$criteria->compare('confirm',$this->confirm);
		$criteria->compare('avatar_url',$this->avatar_url,true);
		$criteria->compare('activ_key',$this->activ_key,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('last_visit',$this->last_visit,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function saveUser(){
        $avatarExist = false;
        $this->create_time = date('Y-m-d');
        $this->activ_key = Helpers::generateRandomKey(10);
        if ($_FILES['User']['name']['avatar']!=""){
            $avatarExist = true;
            $file = CUploadedFile::getInstance($this, 'avatar');
            $this->avatar_url = Helpers::generateRandomKey(7).'.'.$file->getExtensionName();
        }
        $transaction=$this->dbConnection->beginTransaction();
        if ($this->save()){
            $subject = "Подтверждение регистрации";//тема сообщения
            $message = "Здравствуйте! Спасибо за регистрацию на сайте fifa-challenge.tw1.ru\nВаш Origin ID: ".$this->origin."\n";
            $message .= "Перейдите по ссылке, чтобы активировать ваш аккаунт:\nhttp://fifa-challenge.tw1.ru/user/activate";
            $message .= "?email=".$this->email."&code=".$this->activ_key."\nС уважением, Администрация сайта";//содержание сообщение
            if ($avatarExist){
                $big = Yii::getPathOfAlias('webroot').'/images/avatars/';
                $small = Yii::getPathOfAlias('webroot').'/images/thumbAvatars/';
                if (Helpers::resizeImage($file, $big, $this->avatar_url, 500) && Helpers::resizeImage($file, $small, $this->avatar_url, 100, null, $big.$this->avatar_url)){
                    $transaction->commit();
                    Helpers::smtpMail($this->email, $subject, $message );
                    return true;
                } else {
                    $transaction->rollback();
                    $this->addError('avatar', 'Ошибка при загрузке изображения.');
                    return false;
                }
            } else {
                $transaction->commit();
                Helpers::smtpMail($this->email, $subject, $message );
                return true;
            }
        } else {
            $transaction->rollback();
            return false;
        }
    }

    public function activate($email, $code){
        $find = User::model()->findByAttributes(array('email'=>$email));
        if (isset($find)&&$find->confirm) {
            return 'Аккаунт с этим email-адресом уже активирован.';
        } elseif(isset($find->activ_key) && ($find->activ_key===$code)) {
            $find->confirm = 1;
            $find->activ_key = null;
            $find->saveAttributes(array('confirm', 'activ_key'));
            return 'Вы успешно активировали аккаунт. Теперь Вы можете зайти на сайт под своим логином и паролем.';
        } else {
            return 'Некорректный URL.';
        }
    }

    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new UserIdentity($this->email,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            $duration=$this->rememberMe ? 3600*24*365 : 0; // 365 дней
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }
        else {
            switch ($this->_identity->errorCode){
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('email','Такой email-адрес не зарегистрирован');
                    break;
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('password','Неверный пароль');
                    break;
                case UserIdentity::ERROR_NOT_CONFIRM:
                    $this->addError('email','Ваша учётная запись не активирована');
                    break;
                case UserIdentity::ERROR_BANNED:
                    $this->addError('email','Ваш аккаунт заблокирован');
                    break;
                default:
                    $this->addError('email','Ошибка авторизации');
                    break;
            }
            return false;
        }
    }

    public function sendRecoveryMail($email){
        $find = User::model()->findByAttributes(array('email'=>$email));
        if (isset($find)) {
            $find->activ_key = Helpers::generateRandomKey(15);
            $find->saveAttributes(array('activ_key'));
            $subject = "Восстановление пароля";//тема сообщения
            $message = "Здравствуйте! Ваш Origin ID: ".$find->origin."\n";
            $message .= "Для смены пароля на сайте fifa-challenge.tw1.ru перейдите по ссылке:\n";
            $message .= "http://fifa-challenge.tw1.ru/user/recovery?email=".$find->email."&code=".$find->activ_key."\nС уважением, Администрация сайта";//содержание сообщение
            Helpers::smtpMail($email, $subject, $message);
            return 'На Ваш email-адрес отправлено письмо. Перейди по ссылке в нём для смены пароля.';
        }  else {
            return 'Такой email-адрес не зарегистрирован.';
        }
    }

    public function recovery($email, $code){
        $find = User::model()->findByAttributes(array('email'=>$email, 'activ_key'=>$code));
        if (isset($find)) {
            $find->activ_key = null;
            $find->saveAttributes(array('activ_key'));
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($email, $password){
        $find = User::model()->findByAttributes(array('email'=>$email));
        if (isset($find)) {
            $find->password = crypt($password);
            $find->saveAttributes(array('password'));
            return true;
        } else {
            return false;
        }
    }

    public function updateUser($user){
        if (!empty($user['password']) && !empty($user['rePassword'])){
            if (empty($user['oldPassword'])){
                $this->addError('oldPassword', 'Введите старый пароль');
                return false;
            } elseif($user['password']!==$user['rePassword']){
                $this->addError('rePassword', 'Повторите новый пароль в точности');
                return false;
            } elseif (crypt($user['oldPassword'],$this->password)!==$this->password){
                $this->addError('oldPassword', 'Старый пароль неверен');
                return false;
            } elseif ((!empty($user['password']) && empty($user['rePassword'])) || (empty($user['password']) && !empty($user['rePassword']))){
                $this->addError('password', 'Необходимо заполнить все поля паролей');
                return false;
            } else {
                $avatarExist = false;
                $this->attributes = $user;
                $this->password = crypt($this->password);
                if ($_FILES['User']['name']['avatar']!=""){
                    $avatarExist = true;
                    $file = CUploadedFile::getInstance($this, 'avatar');
                    $this->avatar_url = Helpers::generateRandomKey(7).'.'.$file->getExtensionName();
                }
                $transaction=$this->dbConnection->beginTransaction();
                if ($this->save()){
                    if ($avatarExist){
                        $big = Yii::getPathOfAlias('webroot').'/images/avatars/';
                        $small = Yii::getPathOfAlias('webroot').'/images/thumbAvatars/';
                        if (Helpers::resizeImage($file, $big, $this->avatar_url, 500) && Helpers::resizeImage($file, $small, $this->avatar_url, 100, null, $big.$this->avatar_url)){
                            $transaction->commit();
                            return true;
                        } else {
                            $transaction->rollback();
                            $this->addError('avatar', 'Ошибка при загрузке изображения.');
                            return false;
                        }
                    } else {
                        $transaction->commit();
                        return true;
                    }
                } else {
                    $transaction->rollback();
                    return false;
                }
            }
        } else {
            $avatarExist = false;
            $pwd = $this->password;
            $this->attributes = $user;
            $this->password = $pwd;
            if ($_FILES['User']['name']['avatar']!=""){
                $avatarExist = true;
                $file = CUploadedFile::getInstance($this, 'avatar');
                $this->avatar_url = Helpers::generateRandomKey(7).'.'.$file->getExtensionName();
            }
            $transaction=$this->dbConnection->beginTransaction();
            if ($this->save()){
                if ($avatarExist){
                    $big = Yii::getPathOfAlias('webroot').'/images/avatars/';
                    $small = Yii::getPathOfAlias('webroot').'/images/thumbAvatars/';
                    if (Helpers::resizeImage($file, $big, $this->avatar_url, 500) && Helpers::resizeImage($file, $small, $this->avatar_url, 100, null, $big.$this->avatar_url)){
                        $transaction->commit();
                        return true;
                    } else {
                        $transaction->rollback();
                        $this->addError('avatar', 'Ошибка при загрузке изображения.');
                        return false;
                    }
                } else {
                    $transaction->commit();
                    return true;
                }
            } else {
                $transaction->rollback();
                return false;
            }
        }
    }

    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if ($this->isNewRecord)
                $this->password = crypt($this->password);
            return true;
        }
        else
            return false;
    }
}
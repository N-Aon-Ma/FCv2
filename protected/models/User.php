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
            //TODO-не работает валидация файла, также надо сделать миниатюру. Вобщем с файлами жопа
            array('avatar', 'file', 'types'=>'jpeg, jpg, png, gif', 'safe'=>true, 'maxFiles'=>1, 'allowEmpty'=>true),
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
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
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

    public function generate_code($len) //запускаем функцию, генерирующую код
    {
        $hours = date("H"); // час
        $minuts = substr(date("H"), 0 , 1);// минута
        $mouns = date("m");    // месяц
        $year_day = date("z"); // день в году
        $str = $hours . $minuts . $mouns . $year_day; //создаем строку

        $str = md5(md5($str)); //дважды шифруем в md5
        $str = strrev($str);// реверс строки
        $str = substr($str, 3, $len); // извлекаем $len символов, начиная с 3
        // Вам конечно же можно постваить другие значения, так как, если взломщики узнают, каким именно способом это все генерируется, то в защите не будет смысла.

        $array_mix = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        srand ((float)microtime()*1000000);
        shuffle ($array_mix);
        //Тщательно перемешиваем, соль, сахар по вкусу!!!
        return implode("", $array_mix);
    }

    public function saveUser(){
        $this->create_time = date('Y-m-d');
        $this->activ_key = $this->generate_code(10);
        $file = CUploadedFile::getInstance($this, 'avatar');
        $this->avatar_url = $this->generate_code(10).'.'.$file->getExtensionName();
        $destPath = Yii::getPathOfAlias('webroot').'/images/avatars/'.$this->avatar_url;
        if ($file->saveAs($destPath)){
            if ($this->save() && $file->saveAs($destPath)){
                $subject = "Подтверждение регистрации";//тема сообщения
                $message = "Здравствуйте! Спасибо за регистрацию на сайте fifa-challenge.tw1.ru\nВаш Origin ID: ".$this->origin."\n";
                $message .= "Перейдите по ссылке, чтобы активировать ваш аккаунт:\nhttp://fifa-challenge.tw1.ru/user/activate";
                $message .= "?email=".$this->email."&code=".$this->activ_key."\nС уважением, Администрация сайта";//содержание сообщение
                $this->smtpmail($this->email, $subject, $message );
                return true;
            } else {
                return false;
            }
        } else{
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
            $find->activ_key = $this->generate_code(15);
            $find->saveAttributes(array('activ_key'));
            $subject = "Восстановление пароля";//тема сообщения
            $message = "Здравствуйте! Ваш Origin ID: ".$find->origin."\n";
            $message .= "Для смены пароля на сайте fifa-challenge.tw1.ru перейдите по ссылке:\n";
            $message .= "http://fifa-challenge.tw1.ru/user/recovery?email=".$find->email."&code=".$find->activ_key."\nС уважением, Администрация сайта";//содержание сообщение
            $this->smtpmail($email, $subject, $message);
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
                $this->attributes = $user;
                $this->password = crypt($this->password);
                if ($this->save()){
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            $pwd = $this->password;
            $this->attributes = $user;
            $this->password = crypt($pwd);
            if ($this->save()){
                return true;
            } else {
                return false;
            }
        }
    }

    public function smtpmail($mail_to, $subject, $message, $headers='') {
        $config['smtp_username'] = 'fifa-challenge@mail.ru'; //Смените на имя своего почтового ящика.
        $config['smtp_port'] = '25'; // Порт работы. Не меняйте, если не уверены.
        $config['smtp_host'] = 'smtp.mail.ru'; //сервер для отправки почты
        $config['smtp_password'] = 'mailrrruuu33kms'; //пароль
        $config['smtp_charset'] = 'UTF-8'; //кодировка сообщений.
        $config['smtp_from'] = 'fifa-challenge'; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"
        $SEND =   "Date: ".date("D, d M Y H:i:s") . " UT\r\n";
        $SEND .=   'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
        if ($headers) $SEND .= $headers."\r\n\r\n";
        else
        {
            $SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
            $SEND .= "MIME-Version: 1.0\r\n";
            $SEND .= "Content-Type: text/plain; charset=\"".$config['smtp_charset']."\"\r\n";
            $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
            $SEND .= "From: \"".$config['smtp_from']."\" <".$config['smtp_username'].">\r\n";
            $SEND .= "To: $mail_to <$mail_to>\r\n";
            $SEND .= "X-Priority: 3\r\n\r\n";
        }
        $SEND .=  $message."\r\n";
        if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
            return false;
        }

        if (!$this->server_parse($socket, "220", __LINE__)) return false;

        fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");
        if (!$this->server_parse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "AUTH LOGIN\r\n");
        if (!$this->server_parse($socket, "334", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
        if (!$this->server_parse($socket, "334", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
        if (!$this->server_parse($socket, "235", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
        if (!$this->server_parse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");

        if (!$this->server_parse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "DATA\r\n");

        if (!$this->server_parse($socket, "354", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, $SEND."\r\n.\r\n");

        if (!$this->server_parse($socket, "250", __LINE__)) {
            fclose($socket);
            return false;
        }
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        return TRUE;
    }

    public function server_parse($socket, $response, $line = __LINE__) {
        while (substr($server_response, 3, 1) != ' ') {
            if (!($server_response = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($server_response, 0, 3) == $response)) {
            return false;
        }
        return true;
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
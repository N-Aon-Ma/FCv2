<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_NOT_CONFIRM = 3;
    const ERROR_BANNED = -100;
    private $_id;
    public $email;
    public $origin;

    public function __construct($email,$password)
    {
        $this->email=$email;
        $this->password=$password;
    }

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $user=User::model()->findByAttributes(array('email'=>$this->email));
        if($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if ($user->password!==crypt($this->password, $user->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if ($user->confirm==false)
            $this->errorCode=self::ERROR_NOT_CONFIRM;
        else if ($user->role=='ban')
            $this->errorCode=self::ERROR_BANNED;
        else
        {
            $this->_id=$user->id;
            $this->setState('name', $user->origin);
            $this->errorCode=self::ERROR_NONE;
            $this->origin = $user->origin;
        }
        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->_id;
    }
}
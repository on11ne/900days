<?php

class UserIdentity2 extends CUserIdentity
{
    protected $_id;
 
    public function authenticate()
    {
        $user = Users::model()->find('LOWER(email)=?', array(strtolower($this->username)));
        
        if ($user === null)
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        elseif($this->password !== $user->password)
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;            
        }
        else 
        {
            $this->_id = $user->id;
            $this->errorCode = self::ERROR_NONE;
        }
        
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}
<?php

class ServiceUserIdentity extends UserIdentity 
{
    const ERROR_NOT_AUTHENTICATED = 3;

    public $service;
    protected $_id;

    public function __construct($service) 
    {
        $this->service = $service;
    }

    public function authenticate() 
    {
        if ($this->service->isAuthenticated) 
        {
            $user_service = UsersService::model()->find('service=:service AND service_id=:service_id', array(':service' => $this->service->serviceName, ':service_id' => $this->service->id));
           	
            if (isset($user_service->id))
            {
                $user = Users::model()->findByPk($user_service->id);
                
                $this->_id = $user->id;
                $this->setState('name', $user->name.' '.$user->surname);
                $this->setState('role', $user->role);         
            }
            
            $this->errorCode = self::ERROR_NONE;  
        }
        else 
        {
            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
        }
        
        return !$this->errorCode;
    }
    
    public function getId()
    {
        return $this->_id;
    }
}
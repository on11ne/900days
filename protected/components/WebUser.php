<?php

class WebUser extends CWebUser 
{
    private $_model = null;
 
    function getRole() 
    {
        if ($user = $this->getModel())
        {
            return $user->role;
        }
        
        return 'guest';
    }

    function getEmail() 
    {
        if ($user = $this->getModel())
        {
            return $user->email;
        }
        
        return false;
    }
    
    function getName() 
    {
        if ($user = $this->getModel())
        {
            return $user->surname.' '.$user->name;
        }
        
        return false;
    }
    
    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null)
        {
            $this->_model = Users::model()->findByPk($this->id, array('select' => 'role, email, name, surname'));
        }
        
        return $this->_model;
    }
}
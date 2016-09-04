<?php

class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	private $_identity;

	public function rules()
	{
		return array(
			array('username, password', 'required'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'username'=>'Логин',
			'password'=>'Пароль',
			'rememberMe'=>'Запомнить меня',
		);
	}

	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = 3600*24*30;
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		}
		else
			return false;
	}
}

<?php

class Users extends CActiveRecord
{
	public function tableName()
	{
		return 'users';
	}

	public function rules()
	{
		return array(
        
            // registration
			array('email, password', 'required', 'message' => 'Вы не заполнили поле {attribute}', 'on' => 'registration'),
            array('name, surname, email, password, reg_type, role, hash, crt_date, ip', 'filter', 'filter' => 'trim', 'on' => 'registration'),
            array('name, surname, email, password, reg_type, role, hash, crt_date, ip', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'registration'),
            array('email', 'email', 'message' => 'Вы ввели неверный Email', 'on' => 'registration'),
            array('email', 'loginunique', 'on' => 'registration'),
            array('role', 'default', 'value' => 'user', 'on' => 'registration'),
            
            // login
            array('email, password', 'required', 'message' => 'Вы не заполнили поле {attribute}', 'on' => 'login'),
            array('email, password', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'login'),
            array('email, password', 'filter', 'filter' => 'trim', 'on' => 'login'),
            array('email', 'email', 'message' => 'Вы ввели неверный Email', 'on' => 'login'),
			array('password', 'authenticate', 'on' => 'login'),
            
            // forgot
            array('email', 'required', 'message' => 'Вы не заполнили поле {attribute}', 'on' => 'forgot'),
            array('email', 'filter', 'filter' => 'trim', 'on' => 'forgot'),
            array('email', 'email', 'message' => 'Вы ввели неверный Email', 'on' => 'forgot'),
            array('email', 'exist', 'message' => 'Данного Email-а нет в списке зарегистрированных пользователей', 'on' => 'forgot'),
            array('email', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'forgot'),
            
            // social_reg
            array('email, name, surname, role, crt_date, ip', 'filter', 'filter' => 'trim', 'on' => 'social_reg'),
            array('email, name, surname, role, crt_date, ip', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'social_reg'),
            array('role', 'default', 'value' => 'user', 'on' => 'social_reg'),

			array('id, email, password, hash, name, surname, role', 'safe', 'on' => 'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Адрес эл. почты',
			'password' => 'Пароль',
			'hash' => 'Hash',
			'name' => 'Имя',
			'surname' => 'Фамилия',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('surname',$this->surname,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public function authenticate() 
    {
        if (!$this->hasErrors())
        {
            $identity = new UserIdentity($this->email, $this->password);
            
            $identity->authenticate();
                    
            switch($identity->errorCode)
            {
                case UserIdentity::ERROR_NONE: 
                {
                    Yii::app()->user->login($identity, 3600*24*30);
                    break;
                }
                default: 
                {
                    $this->addError('password', 'Email или пароль не подходит');
                    break;
                }
            }
        }
    }
    
    /**
     * Проверка на уникальность
     */
    public function loginunique()
    {
        $model = Users::model()->find('email=:email', array(':email' => $this->email));
        
        if (isset($model->email) && !empty($model->email))
        {
            $this->addError('email', 'Данный E-mail уже зарегистрирован');
        }
    }
}
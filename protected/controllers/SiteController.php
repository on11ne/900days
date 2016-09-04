<?php

class SiteController extends FrontController
{
    public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array(
                'allow',
				'actions' => array('social', 'index', 'error', 'test'),
				'users' => array('*'),
			),
			array(
                'deny',
				'actions' => array('logout'),
				'users' => array('?'),
			),
            array(
                'deny',
				'actions' => array('login', 'register', 'forgot_password'),
				'users' => array('@'),
			),
		);
	}
    
    public function actions()
    {
        return array(
            'ajaxwidgetvalidate' => 'application.controllers.actions.AjaxValidateForWidgetAction',
        );
    }
    
    /**
     * Для тестов
     */
    public function actionTest()
    {
        
    }
    
    /**
     * Главная
     */
    public function actionIndex()
    {
        $this->pageTitle = 'Главная';
        $this->pageKeywords = 'Главная';
        $this->pageDescription = 'Главная';
        
        // Для шэринга
        $clientScript = Yii::app()->clientScript;

        $clientScript->registerMetaTag("Заголовок", null, null, array('property' => "og:title"));
        $clientScript->registerMetaTag(Yii::app()->getBaseUrl(true), null, null, array('property' => "og:url"));
        $clientScript->registerMetaTag("Описание", null, null, array('property' => "og:description"));
        $clientScript->registerMetaTag(Yii::app()->getBaseUrl(true) . '/images/share.jpg', null, null, array('property' => "og:image"));

        $clientScript->registerMetaTag("Заголовок", 'title', null);
        $clientScript->registerMetaTag("Сдость выиграть поездку в Сочи на круизёр-камп", 'description', null);
        $clientScript->registerLinkTag('image_src', null, Yii::app()->getBaseUrl(true) .'/images/share.jpg');
        
        $this->render('index');
    }
    
    /**
     * API - Авторизация
     */
    public function actionLogin()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $login = new Users('login'); 
        
            if (isset($_POST['username']) && isset($_POST['password']))
            {
                $login->email = $_POST['username'];
                $login->password = $_POST['password'];
                
                if ($login->validate()) 
                {
                    echo '{ error: 0, message: "" }';
                }
                else
                {
                    echo '{ error: 1, message: "Неправильное имя пользователя или пароль" }';
                }
            }
        }
    }
    
    /**
     * API - Регистрация
     */
    public function actionRegister()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $registration = new Users('registration');
        
            if (isset($_POST['username']) && isset($_POST['password']))
            {
                $registration->email = $_POST['username'];
                $registration->password = $_POST['password'];
                
                if ($registration->validate())
                {
                    $old_password = $registration->password;
                    $registration->password = md5(Yii::app()->params['salt'].$registration->password);
                    
                    $registration->name = '';
                    $registration->surname = '';
                    $registration->reg_type = 0; 
                    $registration->role = 'user';
                    $registration->hash = '';
                    $registration->ip = $_SERVER["REMOTE_ADDR"];
                    $registration->crt_date = date('Y-m-d H:i:s');
                    
                    if ($registration->save())
                    {
                        $identity = new UserIdentity($registration->email, $old_password);
                        $identity->authenticate();
                        Yii::app()->user->login($identity, 3600*24*30);
                        
                        echo '{ error: 0, message: "" }';
                    }
                    else
                    {
                        echo '{ error: 1, message: "Неправильное имя пользователя или пароль" }';
                    }
                }
                else
                {
                    echo '{ error: 1, message: "Неправильное имя пользователя или пароль" }';
                }
            }
        }
    }
    
    /**
     * API - Напомнить пароль
     */
    public function actionForgot_password()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            $model = new Users('forgot');
        
            if (isset($_POST['username'])) 
            {
                $model->email = $_POST['username'];
                
                if ($model->validate('forgot'))
                {
                    $new_password = rand(1000000, 9999999);
                    
                    $up = Users::model()->find('email=:email', array(':email' => $model->email));
                    $up = md5(Yii::app()->params['salt'].$new_password);
                    $up->update();
                    
                    $from = Yii::app()->params['adminEmail'];
                    $name = 'Техническая поддержка '.Yii::app()->name;
                    $to = $model->email;
                    $subj = 'Напоминание пароля на сайте '.Yii::app()->name;
                    
                    $mail = new YiiMailer('forgot');
                    $mail->setData(array(
                        'new_password' => $new_password
                    ));
                    $mail->setFrom($from, $name);
                    $mail->setTo($to);
                    $mail->setSubject($subj);
                    //$mail->setSmtp('smtp.mandrillapp.com', 587, 'tls', true, 'shipilov@e-produce.ru', 'K4-KGMMD1C-46r2TiTsK_g');
                    $mail->send();
                    
                    echo '{ error: 0, message: "" }';
                }
                else
                {
                    echo '{ error: 1, message: "Не найдена учётная запись" }';
                }
      		}
        }
    }
    
    /**
     * Авторизация и регистрация через социальные сети
     */
    public function actionSocial()
    {
        $service = Yii::app()->request->getQuery('service');

	    if (isset($service))
        {
            //if (!isset($_SERVER['HTTP_REFERER']))
            //{
                $redir = 'http://'.$_SERVER["HTTP_HOST"];
            //} 
            //else 
            //{
                //$redir = $_SERVER['HTTP_REFERER'];
            //}
            
	        $authIdentity = Yii::app()->eauth->getIdentity($service);
	        $authIdentity->redirectUrl = $redir;
	        $authIdentity->cancelUrl = $redir;

	        if ($authIdentity->authenticate())
            {
	            $identity = new ServiceUserIdentity($authIdentity);

                if ($identity->authenticate())
                {
                    // Проверка на привязку
                    $soc = UsersService::model()->find('service=:service AND service_id=:service_id', array(':service' => $identity->service->serviceName, ':service_id' => $identity->service->id));
                    
                    if (isset($soc->service_id) && !empty($soc->service_id)) // Если привязан к сервису
	            	{
                        $exist3 = Users::model()->find('id=:id', array(':id' => $soc->id));
                        
                        Yii::app()->user->login($identity, 3600*24*30);
                        $authIdentity->redirect();
	            	}
	            	else // Если не привязан к сервису
	            	{
	            	    switch ($identity->service->serviceName)
                        {
                            case 'vkontakte':
                                $reg_type = 3;
                                break;

                            case 'odnoklassniki':
                                $reg_type = 2;
                                break;

                            case 'facebook':
                                $reg_type = 1;
                                break;
                        }

                        $model = new Users('social_reg');
                        $fullname = explode(' ', $identity->service->name);
                        if (isset($fullname[0])) { $model->name = $fullname[0]; } else { $model->name = ''; }
                        if (isset($fullname[1])) { $model->surname = $fullname[1]; } else { $model->surname = ''; }
                        
                        if (isset($identity->service->email) && !empty($identity->service->email)) 
                        {
                            $model->email = $identity->service->email;
                        }
                        else
                        {
                            $model->email = '';
                        }
                        
                        $model->hash = '';
                        $model->password = '';
                        $model->role = 'user';
                        
                        $model->ip = $_SERVER["REMOTE_ADDR"];
                        $model->crt_date = date('Y-m-d H:i:s');
                        $model->reg_type = $reg_type;
                        $model->save();
                        
                        $model2 = new UsersService();
                        $model2->id = $model->id;
                        $model2->service = $identity->service->serviceName;
                        $model2->service_id = $identity->service->id;
                        $model2->save();
                        
                        if (isset($identity->service->photo_medium) && !empty($identity->service->photo_medium))
                        {
                            @copy($identity->service->photo_medium, $_SERVER['DOCUMENT_ROOT'].'/uploads/social/'.$model->id.'_'.$reg_type.'.jpg');
                        }
                        
                        if ($identity->authenticate())
                        {
                            Yii::app()->user->login($identity, 3600*24*30);
                            $this->redirect('/');
                        }
	            	}
                }
	        }
	    }
    }
    
    /**
     * Выход
     */
    public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect('/');
	}
    
    /**
     * Вывод ошибки
     */
	public function actionError()
	{
	    if ($error = Yii::app()->errorHandler->error)
	    {
	    	if (Yii::app()->request->isAjaxRequest)
            {
                echo $error['message'];
            }
	    	else
            {
                $this->render('error', $error);
            }	
	    }
	}
}
<?php

class BackController extends CController
{
    public $layout = '//layouts/back';
    
    public $menu = array();
    public $breadcrumbs = array();
    
    public function init()
   	{
   	    Yii::app()->getClientScript()->registerCoreScript('jquery');
        
        $slave = Yii::createComponent('application.extensions.bootstrap.components.Bootstrap');
        Yii::app()->setComponent('bootstrap', $slave);
        
        Yii::app()->user->setStateKeyPrefix('_admin');
        Yii::app()->user->loginUrl = array('administration/default/login');
        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'administration/default/error',
            )
        ));
   	}
}
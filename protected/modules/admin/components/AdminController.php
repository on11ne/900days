<?php

class AdminController extends Controller
{
	public $layout = 'application.modules.admin.views.layouts.main';
	public function init()
	{
	}

	protected function beforeAction($action)
	{
		parent::beforeAction($action);

		if (Yii::app()->user->isGuest && $this->id.'/'.$action->id !== 'default/login')
			Yii::app()->user->loginRequired();

		return true;
	}

	public function filters()
	{
	    return array(
	        'accessControl', // perform access control for CRUD operations
	        array('ext.yiibooster.filters.BoosterFilter')
	    );
	}
}
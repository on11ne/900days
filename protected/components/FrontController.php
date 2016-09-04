<?php

class FrontController extends CController
{
    public $layout = '//layouts/front';
    public $breadcrumbs = array();
    
    public $pageTitle;
    public $pageKeywords;
    public $pageDescription;
    
    public function init()
   	{
   	    Yii::app()->getClientScript()->registerCoreScript('jquery');
   	}
}
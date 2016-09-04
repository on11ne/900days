<?php

class ItemsController extends FrontController
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
				'actions' => array('years'),
				'users' => array('*'),
			),
            /*
			array(
                'deny',
				'actions' => array(''),
				'users' => array('?'),
			),
            array(
                'deny',
				'actions' => array(''),
				'users' => array('@'),
			),*/
		);
	}
    
    /**
     * API - Таймлайн
     */
    public function actionYears()
    {
        if (Yii::app()->request->isAjaxRequest)
        {
            if (
                isset($_GET['active']) && $_GET['active'] == 1 && 
                isset($_GET['target']) && $_GET['target'] == 'timeline'
            )
            {
                $info = Yii::app()->db->createCommand()
                    ->select('YEAR(start) year_start')
                    ->from('items')
                    ->where('start<>"0000-00-00" AND end="0000-00-00"')
                    ->group('YEAR(start)')
                    ->queryAll();
                    
                if (!empty($info))
                {
                    $str = '';
                    foreach ($info as $item)
                    {
                        $str .= $item['year_start'].',';
                    }
                    $str = substr($str, 0, -1);
                    
                    echo '{ error: 0, result: ['.$str.'] }';
                }
                else
                {
                    echo '{ error: 0, message: "Не удалось получить список годов" }';
                }
            }
        }
    }
}
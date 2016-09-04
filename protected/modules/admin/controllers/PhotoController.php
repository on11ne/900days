<?php

class PhotoController extends AdminController
{
public $layout='/layouts/column2';

public function actionView($id)
{
$this->render('view',array(
'model'=>$this->loadModel($id),
));
}

public function actionUpdate($id)
{
$model=$this->loadModel($id);

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

if(isset($_POST['Item']))
{
$model->attributes=$_POST['Item'];
if($model->save())
$this->redirect(array('admin'));
}

$this->render('update',array(
'model'=>$model,
));
}

public function actionAdmin()
{
$model=new Item('search');
$model->unsetAttributes();  // clear any default values
if(isset($_GET['Item']))
$model->attributes=$_GET['Item'];

$this->render('admin',array(
'model'=>$model,
));
}

public function loadModel($id)
{
$model=Item::model()->findByPk($id);
if($model===null)
throw new CHttpException(404,'The requested page does not exist.');
return $model;
}

protected function performAjaxValidation($model)
{
if(isset($_POST['ajax']) && $_POST['ajax']==='photo-form')
{
echo CActiveForm::validate($model);
Yii::app()->end();
}
}
}

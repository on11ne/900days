<?php

class ItemController extends AdminController
{
public $layout='/layouts/column2';

/**
* Specifies the access control rules.
* This method is used by the 'accessControl' filter.
* @return array access control rules
*/
public function accessRules()
{
return array(
array('allow',  // allow all users to perform 'index' and 'view' actions
'actions'=>array('index','view'),
'users'=>array('*'),
),
array('allow', // allow authenticated user to perform 'create' and 'update' actions
'actions'=>array('create','update'),
'users'=>array('@'),
),
array('allow', // allow admin user to perform 'admin' and 'delete' actions
'actions'=>array('admin','delete'),
'users'=>array('admin'),
),
array('deny',  // deny all users
'users'=>array('*'),
),
);
}

/**
* Displays a particular model.
* @param integer $id the ID of the model to be displayed
*/
public function actionView($id)
{
$this->render('view',array(
'model'=>$this->loadModel($id),
));
}

/**
* Creates a new model.
* If creation is successful, the browser will be redirected to the 'view' page.
*/
public function actionCreate()
{
$model=new Item;

$model->active = 1;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

if(isset($_POST['Item']))
{
	$persons = $_POST['Item']['persons'];
	unset($_POST['Item']['persons']);

	$tags = $_POST['Item']['tags'];
	unset($_POST['Item']['tags']);

	$model->attributes=$_POST['Item'];

	if($model->save()) {

		$model->persons = NULL;

		if (is_array($persons))
			foreach ($persons as $m)
			{
				$p = new ItemsPersons;
				$p->item_id = $model->id;
				$p->person_id = $m;
				$p->save();
			}

		$model->tags = NULL;

		if (is_array($tags))
			foreach ($tags as $t)
			{
				$p = new ItemsTags;
				$p->item_id = $model->id;
				$p->tag_id = $t;
				$p->save();
			}

		$this->saveData($model);
		$this->saveImages($model->id);

		if($model->save())
			$this->redirect(array('view','id'=>$model->id));
	}
}

$this->render('create',array(
'model'=>$model,
));
}

/**
* Updates a particular model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $id the ID of the model to be updated
*/
public function actionUpdate($id)
{
$model=$this->loadModel($id);
$media_data = $model->media_data;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

if(isset($_POST['Item']))
{
	$persons = $_POST['Item']['persons'];
	unset($_POST['Item']['persons']);

	$tags = $_POST['Item']['tags'];
	unset($_POST['Item']['tags']);

	$model->attributes=$_POST['Item'];

	$model->media_data = $media_data;


	if($model->save()) {

		$model->edited = new CDbExpression('now()');

		$pers = ItemsPersons::model()->findAll('item_id=:item_id', array(':item_id'=>$model->id));
		foreach ($pers as $m)
			$m->delete();
		$model->persons = NULL;

		if (is_array($persons))
			foreach ($persons as $m)
			{
				$p = new ItemsPersons;
				$p->item_id = $model->id;
				$p->person_id = $m;
				$p->save();
			}

		$tgs = ItemsTags::model()->findAll('item_id=:item_id', array(':item_id'=>$model->id));
		foreach ($tgs as $t)
			$t->delete();

		$model->tags = NULL;

		if (is_array($tags))
			foreach ($tags as $t)
			{
				$p = new ItemsTags;
				$p->item_id = $model->id;
				$p->tag_id = $t;
				$p->save();
			}

		$this->saveData($model);
		$this->saveImages($model->id);
		
		if ($model->validate(null, false) && $model->save(false))
			$this->redirect(array('view','id'=>$model->id));
	}
}

$this->render('update',array(
'model'=>$model,
));
}

/**
* Deletes a particular model.
* If deletion is successful, the browser will be redirected to the 'admin' page.
* @param integer $id the ID of the model to be deleted
*/
public function actionDelete($id)
{
if(Yii::app()->request->isPostRequest)
{
// we only allow deletion via POST request
$this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
if(!isset($_GET['ajax']))
$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
}
else
throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
}

/**
* Lists all models.
*/
public function actionIndex()
{
$dataProvider=new CActiveDataProvider('Item');
$this->render('index',array(
'dataProvider'=>$dataProvider,
));
}

/**
* Manages all models.
*/
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

/**
* Returns the data model based on the primary key given in the GET variable.
* If the data model is not found, an HTTP exception will be raised.
* @param integer the ID of the model to be loaded
*/
public function loadModel($id)
{
$model=Item::model()->findByPk($id);
if($model===null)
throw new CHttpException(404,'The requested page does not exist.');
return $model;
}

/**
* Performs the AJAX validation.
* @param CModel the model to be validated
*/
protected function performAjaxValidation($model)
{
if(isset($_POST['ajax']) && $_POST['ajax']==='item-form')
{
echo CActiveForm::validate($model);
Yii::app()->end();
}
}


public function saveData($model)
{
	if (CUploadedFile::getInstance($model, 'media_data'))
	{
		$file = CUploadedFile::getInstance($model, 'media_data');

		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		$type = '';

		/*if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
			$type = 'img';
		} else*/ if ($ext == 'mov' || $ext == 'mp4' || $ext == 'avi') {
			$type = 'video';
		} else if ($ext == 'mp3' || $ext == 'wav') {
			$type = 'audio';
		}

		if (strlen(trim($type)) < 1) {
			$model->addError('media_data', 'Данный формат файла не поддерживается');
		}

		$image_name = uniqid().".".$ext;

        $upload_directory = Yii::getPathOfAlias('webroot') . '/uploads/'.$type;

        $dp = $upload_directory.'/'.$image_name;

        if (!$file->saveAs($dp))
            $model->addError('media_data', 'Файл не может быть сохранен');

		$model->media_data = $image_name;
    }
}

public function saveImages($id)
	{
		$images = CUploadedFile::getInstancesByName('image');
		if (!empty($images))
		{
			foreach ($images as $i)
			{
				$im = new Image();
				$im->obj_id = $id;
				$im->saveImage($i);

				$im->save();
			}
		}
	}

}

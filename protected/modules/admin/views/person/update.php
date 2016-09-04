<?php
$this->breadcrumbs=array(
	'People'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'Создать','url'=>array('create')),
	array('label'=>'Просмотр','url'=>array('view','id'=>$model->id)),
	array('label'=>'Список','url'=>array('admin')),
	);
	?>

	<h1>Редактировать #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
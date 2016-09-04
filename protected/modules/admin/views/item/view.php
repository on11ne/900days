<?php
$this->breadcrumbs=array(
	'Items'=>array('index'),
	$model->title,
);

$this->menu=array(
array('label'=>'Создать','url'=>array('create')),
array('label'=>'Редактировать','url'=>array('update','id'=>$model->id)),
array('label'=>'Удалить','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Список','url'=>array('admin')),
);
?>

<h1>Просмотр #<?php echo $model->id; ?></h1>

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'created',
		'edited',
		array(
			'name'=>'active',
			'type'=>'raw',
            'value'=>$model->active ? "да" : "нет",
		),
		'title',
		array(
			'name'=>'type_id',
			'type'=>'raw',
            'value'=>$model->type->title,
		),
		array(
			'name'=>'tags',
			'type'=>'raw',
            'value'=>$model->renderTags(),
		),
		'body:raw',
		'media_descr:raw',
		'start',
		'end',
		'address',
		'lat',
		'lng',
		array(
			'name'=>'tags',
			'type'=>'raw',
            'value'=>$model->renderTags(),
		),
		array(
			'name'=>'persons',
			'type'=>'raw',
            'value'=>$model->renderPersons(),
		),
		array(
			'name'=>'media_data',
			'type'=>'raw',
            'value'=>$model->renderMedia(),
		),
		array(
			'name'=>'images',
			'type'=>'html',
			'value'=>$model->renderImages(),
		),
),
)); ?>

<?php
$this->breadcrumbs=array(
	'Items'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'Создать','url'=>array('create')),
);

?>

<h1>Контент</h1>

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'item-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		array(
			'name'=>'id',
            'value'=>'CHtml::encode($data->id)',
            'htmlOptions'=>array('width'=>'50'),
		),
		array(
			'name'=>'active',
			'value'=>'$data->active ? "да" : "нет"',
		),		
		array(
			'name'=>'created',
            'value'=>'CHtml::encode($data->created)',
            'htmlOptions'=>array('width'=>'50'),
		),
		'title',
		array(
			'name'=>'edited',
            'value'=>'CHtml::encode($data->edited)',
            'htmlOptions'=>array('width'=>'50'),
		),
		array(
			'name'=>'type_id',
			'type'=>'html',
			'value'=>'$data->type->title',
		),
		'start',
		'end',
		'address',
		'lat',
		'lng',
		array(
			'name'=>'media_data',
			'type'=>'raw',
            'value'=>'$data->renderMediasmall()',
		),
array(
'class'=>'booster.widgets.TbButtonColumn',
),
),
)); ?>

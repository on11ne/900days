<?php
$this->breadcrumbs=array(
	'People'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'Создать','url'=>array('create')),
);

?>

<h1>Люди</h1>

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'person-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'name',
		'lastname',
		'surname',
array(
'class'=>'booster.widgets.TbButtonColumn',
),
),
)); ?>

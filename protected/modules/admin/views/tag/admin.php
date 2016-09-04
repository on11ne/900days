<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'Создать','url'=>array('create')),
);
?>

<h1>Тэги</h1>

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'tag-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'title',
array(
'class'=>'booster.widgets.TbButtonColumn',
),
),
)); ?>

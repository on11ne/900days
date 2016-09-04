<?php
$this->breadcrumbs=array(
	'People',
);

$this->menu=array(
array('label'=>'Create Person','url'=>array('create')),
array('label'=>'Manage Person','url'=>array('admin')),
);
?>

<h1>People</h1>

<?php $this->widget('booster.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

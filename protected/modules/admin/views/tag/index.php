<?php
$this->breadcrumbs=array(
	'Tags',
);

$this->menu=array(
array('label'=>'Create Tag','url'=>array('create')),
array('label'=>'Manage Tag','url'=>array('admin')),
);
?>

<h1>Tags</h1>

<?php $this->widget('booster.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

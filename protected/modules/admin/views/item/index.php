<?php
$this->breadcrumbs=array(
	'Items',
);

$this->menu=array(
array('label'=>'Create Item','url'=>array('create')),
array('label'=>'Manage Item','url'=>array('admin')),
);
?>

<h1>Items</h1>

<?php $this->widget('booster.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

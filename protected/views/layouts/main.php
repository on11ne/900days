<?php
	$this->pageTitle = Yii::app()->name;

	$clientScript = Yii::app()->clientScript;
	$clientScript->registerCoreScript('jquery');
	$clientScript->registerCoreScript('fancybox');
?>


<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
	</head>
	<body>
		<?php echo $content; ?>		
	</body>
</html>

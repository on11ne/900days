<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>">
        <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>">
	</head>
	<body>
		<?php echo $content; ?>
	</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <?php

    Yii::app()->clientScript->registerCoreScript('jquery');
    Yii::app()->clientScript->registerPackage('fancybox');

    Yii::app()->clientScript->registerCssFile(
        Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('admin.assets').'/css/admin.css'
        )
    );

    Yii::app()->clientScript->registerCssFile(
        Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('admin.assets').'/css/datepicker.css'
        )
    );

    Yii::app()->clientScript->registerScriptFile(
    	Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('admin.assets').'/js/js.js'
        )
    );

    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/vendor/ckeditor/ckeditor.js');

    Yii::app()->clientScript->registerScriptFile(
    	Yii::app()->assetManager->publish(
            Yii::getPathOfAlias('admin.assets').'/js/bootstrap-datepicker.js'
        )
    );

	Yii::app()->clientScript->registerScript('fancybox_init', '
        $(".fancybox").fancybox();
    ');

    ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <style type="text/css">
        div.top-menus li {
            display: block;
            float: left;
            margin-left: 10px;
            background: none repeat scroll 0 0 #EFFDFF;
            text-align: center;
        }

        #logo img {
            margin-top: 10px;
        }
    </style>

</head>

<body>
<div id="wrap">
    <div class="container" id="page">
        <div id="header">
            <?php
                $this->widget(
                    'booster.widgets.TbNavbar',
                    array(
                        'brand' => 'Блокада',
                        'fixed' => false,
                        'fluid' => true,
                        'items' => array(
                            array(
                                'class' => 'booster.widgets.TbMenu',
                                'type' => 'navbar',
                                'items' => array(
									array('label' => 'Главная', 'url' => array('default/index')),
									array('label' => 'Теги', 'url' => array('tag/admin')),
									array('label' => 'Люди', 'url' => array('person/admin')),
									array('label' => 'Контент', 'url' => array('item/admin')),
                                    array('label' => 'Выйти', 'url' => array('/site/logout'), 'itemOptions' => array('class'=>'right')),
                                )
                            )
                        )
                    )
                );
            ?>
        </div><!-- header -->

        <?php echo $content; ?>

    </div><!-- page -->
</div>

<br/><br/>

<div id="footer">
    <div class="container">
        &copy; 2016
    </div>
</div><!-- footer -->

</body>
</html>
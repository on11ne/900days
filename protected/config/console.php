<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Snickerface',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'application.widgets.*',
        'application.helpers.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'superadmin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('10.249.0.213','::1'),
		),
	),

	// application components
	'components'=>array(

		'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            'driver' => 'GD',
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'user' => array(
            'allowAutoLogin' => true,
            'loginUrl' => array('admin/default/login')
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(

                '' => 'site/index',
                'gallery' => 'site/gallery',
                'producers' => 'site/producers',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

		'clientScript' => array(
            'packages' => array(
                'fancybox' => array(
                    'baseUrl' => '/js/fancyapps-fancyBox-18d1712',
                    'js' => array(
                        'source/jquery.fancybox.pack.js',
                        'lib/jquery.mousewheel-3.0.6.pack.js',
                        'source/helpers/jquery.fancybox-buttons.js',
                        'source/helpers/jquery.fancybox-media.js',
                        'source/helpers/jquery.fancybox-thumbs.js',
                    ),
                    'css' => array(
                        'source/jquery.fancybox.css',
                        'source/helpers/jquery.fancybox-buttons.css',
                        'source/helpers/jquery.fancybox-thumbs.css',
                    ),
                    'depends' => array('jquery')
                ),
            ),
        ),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'shipilov@e-produce.ru',
	),
);

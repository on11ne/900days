<?php

Yii::setPathOfAlias('booster', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/yiibooster');

return array(
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name' => '900 Дней Блокады Ленинграда',
	'preload' => array('log'),
    'theme' => 'classic',
    
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'application.widgets.*',
        'application.helpers.*',
        'application.extensions.yiibooster.*',
        'application.extensions.yiibooster.components.*',
        
        'application.extensions.YiiMailer.YiiMailer',
        // multi auth
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
	),

	'modules' => array(
		'gii' => array(
			'class'=>'system.gii.GiiModule',
			'generatorPaths' => array(
                'booster.gii',
            ),
			'password' => 'superadmin',
			'ipFilters' => array('77.232.149.249','77.232.154.198','::1'),
		),
		'admin',
	),
    
	'components' => array(
    
        'user' => array(
            'allowAutoLogin' => true,
            'class' => 'WebUser',
            'loginUrl' => array('admin/default/login')
        ),

		'booster' => array(
            'class' => 'application.extensions.yiibooster.components.Booster',
        ),

		'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            'driver' => 'GD',
            'params' => array('directory' => '/opt/local/bin'),
        ),
        
        

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(

                '' => 'site/index',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

        'request' => array(
            //'enableCsrfValidation' => true,
            'enableCookieValidation' => true, 
        ),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),
        
        'imagemod' => array(
            'class' => 'application.extensions.imagemodifier.CImageModifier',
        ),
        
        // multi auth
        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => array( // You can change the providers and their classes.
                'vkontakte' => array(
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'VKontakteOAuthService',
                    'client_id' => '5615935',
                    'client_secret' => 'C1YP1GlpQ48qgX8fIsch',
                ),
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '301698600192164',
                    'client_secret' => '55aa4cb0fa8ec05d7721ec5bc93761c1',
                ),
                'odnoklassniki' => array(
                    // http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'OdnoklassnikiOAuthService',
                    'client_id' => '1248097536',
                    'client_public' => 'CBAGHFGLEBABABABA',
                    'client_secret' => 'EDE6F0E4448D9D366970FB27',
                    'title' => 'Одноклассники',
                ),
                /*
                'google_oauth' => array(
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'GoogleOAuthService',
                    'client_id' => '695995412489-4eo8qbubkmkne2h5eevmdv1oeim8fsbi.apps.googleusercontent.com',
                    'client_secret' => 'S6TW60aK4TLhds5DfJFhJXR5',
                    'title' => 'Google',
                ),
                'yandex_oauth' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'YandexOAuthService',
                    'client_id' => '06c2439ec1994557b06c93ce1253df29',
                    'client_secret' => '25aabc76d0914378bc1c9a2b7db2bfa2',
                    'title' => 'Яндекс',
                ),
                'live' => array(
                    // register your app here: https://manage.dev.live.com/Applications/Index
                    'class' => 'LiveOAuthService',
                    'client_id' => '0000000048102C38',
                    'client_secret' => 'Cn3MdxiPwETIA65I69UP-c2J16CAEV54',
                ),
                'mailru' => array(
                    // register your app here: http://api.mail.ru/sites/my/add
                    'class' => 'MailruOAuthService',
                    'client_id' => '708815',
                    'client_secret' => '7f62eab81ef06cbce324623c5ceb048f',
                ),
                'moikrug' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'MoikrugOAuthService',
                    'client_id' => 'd97bbf94de634c4695f3306126ed5d16',
                    'client_secret' => '4360ab3f50f04c6e93064de5de967b43',
                ),
                */
            ),
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
        'salt' => '7s56!@*S%44Fl7=5',
	),
	'sourceLanguage' => 'ru',
);

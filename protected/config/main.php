<?php

// uncomment the following to define a path alias

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'FIFA Challenge',
    'language'=>'ru',

	// preloading 'log' component
	'preload'=>array(
        'log',
        'config'
    ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool       
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
            'generatorPaths'=>array(
                'application.gii',
             ),
			'password'=>'1234',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),       
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
            'allowAutoLogin'=>true,
            'class'=>'WebUser',
            'guestName'=>'Гость',
	        'loginUrl'=>array('/user/login'),
		),

        'config'=>array(
            'class' => 'DConfig'
        ),

        'authManager' => array(
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => array('guest'),
        ),

		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
            'showScriptName'=>false,
			'urlFormat'=>'path',
			'rules'=>array(
                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=temp',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
            // включаем профайлер
            'enableProfiling'=>true,
            // показываем значения параметров
            'enableParamLogging' => true,
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages

				array(
					'class'=>'CWebLogRoute',
                    'levels'=>'error, warning',
				),

                array(
                    'class'=>'CProfileLogRoute',
                    'report'=>'summary',
                    'levels'=>'profile',
                    'enabled'=>true,
                    // Показывает время выполнения каждого отмеченного блока кода.
                    // Значение "report" также можно указать как "callstack".
                ),

			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'maxmirazh33@gmail.com',
	),
);
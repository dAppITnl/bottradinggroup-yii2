<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'botsignals-apiv1',
		'name' => 'Botsignals-APIv1',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'apiv1\controllers',
    'bootstrap' => ['log'],
		'language' => 'nl-NL',
		'sourceLanguage' => 'en-US',
		'timeZone' => 'Europe/Amsterdam',
    'modules' => [
      'gridview' => [
          'class' => '\kartik\grid\Module',
          // see settings on http://demos.krajee.com/grid#module
      ],
      'datecontrol' => [
          'class' => 'kartik\datecontrol\Module',
          // see settings on http://demos.krajee.com/datecontrol#module
            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'd-m-Y',
                'time' => 'H:i:s A',
                'datetime' => 'd-m-Y H:i:s A',
            ],

            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'Y-m-d',
                'time' => 'H:i:s',
                'datetime' => 'Y-m-d H:i:s',
            ],
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
      ],
      // If you use tree table
      'treemanager' =>  [
          'class' => '\kartik\tree\Module',
          // see settings on http://demos.krajee.com/tree-manager#module
      ],
		],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-apiv1',
						'enableCsrfValidation' => false,
						'parsers' => [
				//			'application/json' => 'yii\web\JsonParser',
						],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-apiv1', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the apiv1
            'name' => 'botsignals-apiv1',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
						'enableStrictParsing' => false,
            'rules' => [
						//	['controller' => 'to3c'],
					//		['class' => 'yii\rest\UrlRule', 'controller' => 'api'],
					//		['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
            ],
        ],
		],
    'params' => $params,
];


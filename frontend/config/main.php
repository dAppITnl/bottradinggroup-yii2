<?php
use cinghie\paypal\filters\FrontendFilter as PaypalFrontendFilter;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'botsignals-frontend',
		'name' => 'Bot Trading Group',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
		'language' => 'nl-NL', // 'en_US', // 'nl_NL', for .nl site
		'sourceLanguage' => 'en-US', // do not change -> https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n
		'timeZone' => 'Europe/Amsterdam',
//		'imagesmap' => 'https://bottradinggroup.nl/images/',
		'modules' => [
      'gridview' => [
          'class' => '\kartik\grid\Module',
          // see settings on http://demos.krajee.com/grid#module
      ],
      'datecontrol' => [
          'class' => '\kartik\datecontrol\Module',
          // see settings on http://demos.krajee.com/datecontrol#module
      ],
      // If you use tree table
      'treemanager' =>  [
          'class' => '\kartik\tree\Module',
          // see settings on http://demos.krajee.com/tree-manager#module
      ],
			/* cinghie Paypal
			'paypal' => [
				'as backend' => PaypalBackendFilter::class,
			],*/
		],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
						//'class' => 'common\helpers\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => false],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'botsignals-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace'],
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
            ],
        ],
        'i18n' => [
          'translations' => [
            'app*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/frontend_messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
						'common*' => [
							'class' => 'yii\i18n\PhpMessageSource',
							'basePath' => '@common/common_messages', //'@app/messages',
							//'sourceLanguage' => 'en-US',
							'fileMap' => [
								'common' => 'common.php',
								'common/error' => 'error.php',
							],
						],
            'giiant*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/frontend_messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
            'models*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/frontend_messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
            'cruds*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/frontend_messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
          ],
        ],
				'reCaptcha' => [
						'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
						'siteKey' => '6LedI9gcAAAAAKtn7-akytV6rCkBqN4K0aazSFhj', // v3: '6Lf1EtgcAAAAACuFgYqtaLqE0lFcS_PvtkXNyt0V',
            'secret' => '6LedI9gcAAAAADaaHDycu0wsVCL2pmdeWRWbIdQ9', // v3: '6LfuIdgcAAAAAEMorWaqeUxTN1ZuVB7EIcwuZdr1',
        ],
				// Bitcko PayPal
				'PayPalRestApi' => [
            'class' => 'common\helpers\PayPalRestApi',
						//'class' => 'bitcko\paypalrestapi\PayPalRestApi',
            //'redirectUrl' => '/membership/paydone', // '/site/make-payment', // Redirect Url after payment
        ],
				// Paypal's own
				'PayPalCheckoutSdk' => [
					'class' => 'common/helpers/PayPalCheckoutSdk/Checkout-PHP-SDK/lib/PayPalCheckoutSdk$',
				],
    ],
    'params' => $params,
];

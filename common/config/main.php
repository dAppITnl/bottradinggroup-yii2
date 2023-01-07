<?php
use cinghie\paypal\components\Paypal as PaypalComponent;
use cinghie\paypal\Paypal as PaypalModule;
// use cinghie\paypal\components\Braintree as BraintreeComponent;

return [
	'aliases' => [
		'@bower' => '@vendor/bower-asset',
		'@npm'   => '@vendor/npm-asset',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'modules' => [
		'datecontrol' =>  [
			'class' => 'kartik\datecontrol\Module',
			// format settings for displaying each date attribute
			'displaySettings' => [
				'date' => 'd-m-Y',
				'time' => 'H:i:s',
				'datetime' => 'd-m-Y H:i:s',
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
		/* cinghie Paypal
			'paypal' => [
			'class' => PaypalModule::class,
			'paypalRoles' => ['admin'],
			'showTitles' => false,
		],*/
	],
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
		'i18n' => [
			'translations' => [
        'common*' => [
          'class' => 'yii\i18n\PhpMessageSource',
          'basePath' => 'common/messages', //'@app/messages',
          //'sourceLanguage' => 'en-US',
          'fileMap' => [
            'common' => 'common.php',
            'common/error' => 'error.php',
          ],
        ],
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => 'common/messages', //'@app/messages',
					//'sourceLanguage' => 'en-US',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
				'giiant*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => 'common/messages', //'@app/messages',
					//'sourceLanguage' => 'en-US',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
				'models*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => 'common/messages', //'@app/messages',
					//'sourceLanguage' => 'en-US',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
				'cruds*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => 'common/messages', //'@app/messages',
					//'sourceLanguage' => 'en-US',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
			],
		],
		/* cinghie Paypal
		'paypal' => [
			'class'        => 'cinghie\paypal\components\Paypal',
			'clientId'     => 'AaB7Day9SR0EaTzf5NmisJVv3z7anPEZ75S4Q1Y_Dx0QFr1x6NwCqCEHpuZRu3q4QC_PXn3NJBswgR46',
			'clientSecret' => 'EC03A8vXRBlygZ4d2eMHzKERkoEx67fi6gdThyFiSBLuoKt3EWjK0Ph-9JCLBiFkBZveNo2PL7uAJMFD',
			'isProduction' => false,
			'config' => [
				'mode' => 'sandbox', // 'sandbox' (development mode) or 'live' (production mode)
				'http.ConnectionTimeOut' => 30,
				'http.Retry' => 1,
				'log.LogEnabled' => YII_DEBUG ? 1 : 0,
				'log.FileName' => '@runtime/logs/paypal.log',
				'log.LogLevel' => 'ERROR',
			]
		],*/
		/*'braintree' => [ // for cinghie Paypal; a paypal service https://www.braintreepayments.com/
			'class' => BraintreeComponent::class,
			'environment' => 'sandbox',
			'merchantId' => 'your_merchant_id',
			'publicKey' => 'your_public_key',
			'privateKey' => 'your_private_key'
		],*/
		/*'hyperwallet' => [ // for cinghie Paypal; a paypal service https://www.hyperwallet.com/
			'class' => HyperwalletComponent::class,
			'username' => 'HYPERWALLET_SERVER',
			'password' => 'HYPERWALLET_PASSWORD',
			'token' => 'HYPERWALLET_PROGRAM_TOKEN',
			'server' => 'https://sandbox.hyperwallet.com'
		],*/
	],
];

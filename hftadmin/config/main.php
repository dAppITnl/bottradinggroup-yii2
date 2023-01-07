<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'btg-hftadmin',
		'name' => 'HFT Admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'hftadmin\controllers',
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
      /* cinghie Paypal
      'paypal' => [
        'as backend' => PaypalBackendFilter::class,
      ],*/
      /*[ -- howto?
        'quill' => [
          'class' => 'bizley\quill\Quill',
          'toolbar' => [
            'toolbarOptions' => [['bold', 'italic', 'underline', 'strike'], [['color' => []]]],
          ],
        ],
      ],*/
		],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-hftadmin',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-hftadmin', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the hftadmin
            'name' => 'advanced-hftadmin',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
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
              'basePath' => '@common/messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
            'giiant*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
              ],
            ],
            'models*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/messages', //'@app/messages',
              //'sourceLanguage' => 'en-US',
              'fileMap' => [
                'app' => 'app.php',
                'app/error' => 'error.php',
                'models' => 'models.php',
              ],
            ],
            'cruds*' => [
              'class' => 'yii\i18n\PhpMessageSource',
              'basePath' => '@common/messages', //'@app/messages',
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
        /*'formatter' => [
          'class' => 'yii\i18n\formatter',
          'thousandSeparator' => '.',
          'decimalSeparator' => ',',
          'currencyCode' => '&euro; '
        ],*/
    ],
    'params' => $params,
];

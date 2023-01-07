<?php

/* @var $this \yii\web\View */
/* @var $content string */

use Yii;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use common\helpers\GeneralHelper;

$getLanguages = GeneralHelper::getLanguages();

AppAsset::register($this);

$imagesmap =  Yii::$app->params['imagesmap'];
$cssfile = '';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
	<link rel="stylesheet" href="/css/theme.1.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<!-- link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" -->
	<link rel="stylesheet" href="/css/fontawesomev6/css/all.css" />
	<!-- script src="/css/fontawesomev6/js/all.js" data-auto-replace-svg="nest"></script -->

	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#000000" />
	<!-- link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" -->
	<?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode( Yii::$app->name ) ?></title>
	<?php $this->head() ?>
	<?php $csstheme = (!Yii::$app->user->isGuest && !empty(Yii::$app->user->identity->sitecsstheme)) ? Yii::$app->user->identity->sitecsstheme : GeneralHelper::SITECSS_DARK;
		Yii::trace('** csstheme='.$csstheme);
		$cssfile = GeneralHelper::createCssFilename($csstheme);
		if (!empty($cssfile)) {
			echo "<link href='/css/".$cssfile."' rel='stylesheet'>\n";
			if ($cssfile == 'base_n.css') echo "<link rel='stylesheet' href='/css/theme.1.css'>\n";
		}
	?>
</head>

<body class="d-flex flex-column h-100">
<div class="bodybackground">
<noscript><?= Yii::t('app', 'You need to enable JavaScript to view this site!') ?></noscript>
<?php if ($cssfile == 'no base_n.css') : ?><img src="/images/background/btg_background.svg" class="backgroundimage"><?php endif; ?>
<?php $this->beginBody() ?>

<header class="app-header">
	<div id="app-header-menu">
	<?php
    NavBar::begin([/*'brandLabel' => Yii::$app->name, 'brandUrl' => Yii::$app->homeUrl,*/ 'options' => ['class' => 'navbar navbar-expand-md navbar-dark header_bg fixed-top', ], ]);
    // $menuItemsLeft = [];
    $menuItemsRight = [];
		//if (!Yii::$app->user->isGuest) {
      /*$menuItemsLeft[] = ['label' => Yii::t('app', 'My Bots'),
        'items' => [
          ['label' => Yii::t('app', 'Overview'), 'url' => ['/bot/index']],
          // ['label' => Yii::t('app', 'Assigned'), 'url' => ['/bot/assigned']],
        ]
      ];*/

      /*$menuItemsLeft[] = ['label' => Yii::t('app', 'Signals'),
				'items' => [
					['label' => Yii::t('app', 'Overview'), 'url' => ['/signal/index']],
					['label' => Yii::t('app', 'Assigned'), 'url' => ['/signal/assigned']],
				]
			];*/
    //}

		$menuItemsRight[] = ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']];

		/*$menuItemsRight[] = ['label' => Yii::t('app', 'Links'),
			'items' => [
				['label' => Yii::t('app', 'Discord (invite)'), 'url' => 'https://discord.com/invite/wR3X5XSJVg'], //'https://discord.gg/7GqAvHccgw'],
				['label' => Yii::t('app', '3Commas'), 'url' => 'https://3commas.io/'],
				['label' => Yii::t('app', 'TradingView'), 'url' => 'https://www.tradingview.com/'],
			]
		];*/

		if (!Yii::$app->user->isGuest) {
			$menuItemsRight[] = ['label' => Yii::t('app', 'Subscriptions'),
				'items' => [
					['label' => Yii::t('app', 'Subsrcibe to a plan'), 'url' => ['/membership/subscribe']],
					['label' => Yii::t('app', 'Active subscriptions'), 'url' => ['/membership/index']],
					['label' => Yii::t('app', 'Expired subscriptions'), 'url' => ['/membership/history']],
				]
			];

	    $menuItemsRight[] = ['label' => Yii::t('app', 'Signals'),
  	    'items' => [
    	   ['label' => Yii::t('app', 'Overview'), 'url' => ['/signal/index']],
    	 //  ['label' => Yii::t('app', 'Assigned'), 'url' => ['/signal/assigned']],
     	 ]
    	];
		}

		$menuItemsRight[] = ['label' => Yii::t('app', 'Info'),
			'items' => [
				['label' => Yii::t('app', 'FAQ'), 'url' => ['/site/faq']],
				['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
				['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
				['label' => Yii::t('app', 'Disclaimer'), 'url' => ['/site/disclaimer']],
				['label' => Yii::t('app', 'Subscription terms'), 'url' => ['/site/membershipterms']],
			]
		];

		if (Yii::$app->user->isGuest) {
			$menuItemsRight[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/site/signup']];
			$menuItemsRight[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
		} else {
			$menuItemsRight[] = ['label' => Yii::t('app', 'My profile'),
				'items' => [
					['label' => Yii::t('app', '{sitelevel}: {username}',
					['sitelevel'=>Yii::$app->user->identity->usr_sitelevel, 'username'=>Yii::$app->user->identity->username]), 'url' => ['/user/index']],
					//['label' => Yii::t('app', 'Profile'), 'url' => ['/user/index']],
					['label' => Yii::t('app', 'Update'), 'url' => ['/user/update']],
					['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
				]
			];
		}
		//if (!YII_LIVEDB) echo "<div class='text-left'>*</div>";
		//if (YII_DEBUG) echo "<div class='text-left'>#</div>";
		//echo '<div class="text-left">' . Nav::widget(['options' => ['class' => 'navbar-nav'],  'items' => $menuItemsLeft,  ]) . '</div>';
		echo '<div class="ml-auto pull-right">'. Nav::widget(['options' => ['class' => 'navbar-nav'], 'items' => $menuItemsRight, ]) . '</div>';
		NavBar::end();

		/*$this->widget('DropDownRedirect', [
			'data' => $getLanguages, // Yii::app()->user->avalaibleLanguages, // data od my dropdownlist
			'url' => $this->createUrl($this->route, array_merge($_GET, array('lang' => '__value__'))), // the url (__value__ will be replaced by the selected value)
			'select' => 'nl-NL', //Yii::app()->user->language, //the preselected value
		]);*/
	?>
	</div>
	<div class="backgrounddiv">
	<?php if ($cssfile != 'base_n.css') : ?>
	<div class="mx-auto text-center mt-5"><br><img src="/images/btg_logo3.png" class="headerimg"></div>
	<?php endif; ?>
  </div>
</header>
<!-- div class="backgroundimage"></div -->
<main role="main" class="flex-shrink-0">
  <div class="<?= (($cssfile == 'base_n.css') ? 'section-main' : '/*container*/') ?>">
    <?= '' //Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [], ]) ?>
    <?= Alert::widget() ?>
    <section id="content" class="container">

<!-- div class="text text-center"><p>Site is in maintenance: short interruptions or a (php) error can occur. Subscribing is not possible until later today. Our appologies for this situation!<br>
Site is in onderhoud: er kunnen korte onderbrekingen of een (php) fout optreden. Tot later vandaag is abonneren niet mogelijk. Onze excuses voor deze situatie!</p></div -->

            <?= $content ?>
        </section>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left footer">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <!-- p class="float-right"><?= Yii::powered() ?></p -->
    </div>
</footer>
<?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage();

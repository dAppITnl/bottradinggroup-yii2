<?php

/* @var $this \yii\web\View */
/* @var $content string */

use hftadmin\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use common\helpers\GeneralHelper;

AppAsset::register($this);

$access = GeneralHelper::checkSiteAccess();
Yii::trace('** view layout main: '.print_r($access, true));


$imagesmap = Yii::$app->params['imagesmap'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

    $menuItemsLeft = [
        //['label' => 'Home', 'url' => ['/site/index']],
    ];
    $menuItemsRight = [
        //['label' => '', 'url' => ['/site/index']],
    ];

    if (Yii::$app->user->isGuest) {
      $menuItemsRight[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
    } else {
			if ($access['hftadmin'] == 'true') {
      	$menuItemsLeft = [
					['label' => Yii::t('app', 'Users'), 'url' => ['/user']],
					['label' => Yii::t('app', 'Payments'), 'url' => ['/userpay']],
					['label' => Yii::t('app', 'Signals'), 'url' => ['/signal']],
					['label' => Yii::t('app', 'Logs'), 'url' => ['/log']],
					['label' => Yii::t('app', 'Admin'), 'url' => ['/admin']],
				];
				/*$menuItemsRight = [
					['label' => Yii::t('app', 'MyAdmin'),
						'items' => [
							['label' => Yii::t('app', '{sitelevel}: {username}', ['sitelevel'=>Yii::$app->user->identity->usr_sitelevel, 'username'=>Yii::$app->user->identity->username]),
																																	 'url' => ['/user/view?id='.Yii::$app->user->id]],
							['label' => Yii::t('app', 'Frontend'), 'url'=> 'https://bottradinggroup.nl', 'linkOptions'=>['target'=>'_blank']],
							['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
						]
					]
      	];*/
			}
			$menuItemsRight[] = ['label' => Yii::t('app', 'Logout'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']];
		}

		if (!YII_LIVEDB) echo "<div class='text-warning'>[DevDB]</div>";
		if (YII_DEBUG) echo "<div class='text-warning'>[Debug]</div>";

		echo '<div class="text-left">' . Nav::widget(['options' => ['class' => 'navbar-nav'],  'items' => $menuItemsLeft,  ]) . '</div> <div class="text-warning">' . date('d-M-y H:i') . '</div>';
    echo '<div class="ml-auto pull-right">' . Nav::widget(['options' => ['class' => 'navbar-nav'], 'items' => $menuItemsRight, ]) . '</div>';

    /*echo Nav::widget([
        'options' => ['class' => 'nav navbar-nav navbar-right'],
        'items' => $menuItemsRight,
    ]);*/
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container"><p>&nbsp;</p>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<div class="mt-5">&nbsp;</div>
<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();

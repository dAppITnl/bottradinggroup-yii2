<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

$languages = GeneralHelper::getLanguages();
$userId = Yii::$app->user->id;
$userIdentity = Yii::$app->user->identity;
$discordRoles = GeneralHelper::getDiscordRoles();
$roles = [];
foreach(explode(',', $userIdentity->usr_discordroles) as $role) $roles[] = $discordRoles[ $role ];
asort($roles, SORT_STRING | SORT_FLAG_CASE);
$userDiscordRoles = implode(',<br>', $roles);

?>
<div class="user-index">
  <div class="body-content">

		<div class="container">

	    <div class="row">
  	    <div class="col text-center">
    	    <p><h1><?= Yii::t('app', 'Your profile') ?>:</h1></p>
      	</div>
    	</div>

	    <div class="row">
  	    <div class="col">
					<p><h3><?= Yii::t('app', 'Profile') ?>:</h3>
						<table class="table-100" border="1">
      				<tr><th><?= Yii::t('app', 'Username') ?>: </th><td><span class="table-title"><?= $userIdentity->username ?></span></td></tr>
							<!-- tr><th><?= Yii::t('app', 'Id')  ?>: </th><td><?= $userId ?></td></tr -->
							<tr><th><?= Yii::t('app', 'Email')  ?>: </th><td><?= $userIdentity->email ?></td></tr>
							<tr><th><?= Yii::t('app', 'For payments via Utrust')  ?>: </th><td><?= $userIdentity->usr_firstname .' '. $userIdentity->usr_lastname .', '. $userIdentity->usr_countrycode ?></td></tr>
							<tr><th><?= Yii::t('app', 'Language')  ?>: </th><td><?= $languages[ $userIdentity->usr_language ] ?></td></tr>
							<tr><th valign="top"><?= Yii::t('app', 'Remarks')  ?>: </th><td><?= $userIdentity->remarks ?></td></tr>
							<tr><th><?= Yii::t('app', 'Created')  ?>: </th><td><?= $userIdentity->createdt ?></td></tr>
						</table>
					</p>

					<p><h3><?= Yii::t('app', 'Discord') ?>:</h3>
						<table class="table-100" border="1">
							<tr><th><?= Yii::t('app', 'Username') ?>: </th><td><b><?= $userIdentity->usr_discordusername ?></b></td></tr>
							<tr><th><?= Yii::t('app', 'Nick')  ?>: </th><td><?= $userIdentity->usr_discordnick ?></td></tr>
							<tr><th><?= Yii::t('app', 'Id')  ?>: </th><td><?= $userIdentity->usr_discordid ?></td></tr>
							<tr><th><?= Yii::t('app', 'Joined at')  ?>: </th><td><?= $userIdentity->usr_discordjoinedat ?></td></tr>
							<tr><th valign="top"><?= Yii::t('app', 'Roles')  ?>: </th><td><?= $userDiscordRoles ?></td></tr>
						</table>
					</p>

					<p><h3><?= Yii::t('app', 'Crypto') ?>:</h3>
						<table class="table-100" border="1">
							<tr><th><?= Yii::t('app', 'MoralisId')  ?>: </th><td><?= $userIdentity->moralisid ?></td></tr>
						</table>
					</p>

					<p><?= HTML::a( Yii::t('app', 'Update your profile'), ['user/update', 'id'=>$userId], ['class'=>'knop-3']) ?></p>
					<p class="text-wit"><?= Yii::t('app', 'If you forgot your password you can ') . Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset'], ['class'=>'resetpassword']) ?></p>
    	  </div>
    	</div>

		</div>
	</div>
</div>



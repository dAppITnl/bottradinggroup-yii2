<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

//use yii\helpers\Url; //manager;
//use \kartik\grid\GridView;
//use \frontend\models\User;

$yesNos = GeneralHelper::getYesNos();
$userSignals = GeneralHelper::getUserSignals(Yii::$app->user->id, Yii::$app->user->identity->usr_language, false);

?>
<div class="site-index">
  <div class="body-content">
		<div class="container">

	    <div class="row">
  	    <div class="col">
    	    <h1><?= Yii::t('app', 'Assigned signals') ?></h1>
      	</div>
    	</div>

<?php foreach($userSignals as $umbid => $usermember) : ?>
			<hr>
    	<h2><small><?= Yii::t('app', 'Subscription') ?>:</small> <?= $usermember['umbname'] ?></h2>

			<table border="1">
				<tr><th><?= Yii::t('app', 'Membership') ?>:</th><td><?= $usermember['mbrtitle'] ?></td></tr>
      	<tr><th><?= Yii::t('app', 'Startdate')  ?>:</th><td><?= $usermember['startdate'] ?></td></tr>
      	<tr><th><?= Yii::t('app', 'Enddate')    ?>:</th><td><?= $usermember['enddate'] ?></td></tr>
			</table>

	<?php foreach($usermember['signals'] as $catTitle => $signals) : ?>
			<h3><small><?= Yii::t('app', 'Signal category') ?>:</small> <?= $catTitle ?></h3>

		<?php foreach($signals as $sigid => $signal) : ?>
			<h4><small><?= Yii::t('app', 'Signal') ?>:</small> <?= $signal['signame'] ?></h4>

			<table border="1">
				<tr><th><?= Yii::t('app', 'max bots')      ?>:</th><td><?= $signal['maxbots'] .' '.
					HTML::a(Yii::t('app', 'Update signal'), ['/membership/updatebotsignal', 'id'=>$signal['bsgid']], ['class'=>'btn btn-info']) .' '.
					(/*$signr < $maxsignals*/true ? HTML::a(Yii::t('app', 'Add a signal'), ['/membership/addbotsignal', 'id'=>$signal['ubtid']], ['class'=>'btn btn-primary'])
																: '('.YII::t('app', 'Max nr of signals reached').')' )?></td></tr>
				<tr><th><?= Yii::t('app', 'Base')          ?>:</th><td><?= $signal['sigbase'] ?></td></tr>
				<tr><th><?= Yii::t('app', 'Quote')         ?>:</th><td><?= $signal['sigquote'] ?></td></tr>
				<tr><th><?= Yii::t('app', 'Signal active') ?>:</th><td><?= $yesNos[ $signal['bsgactive'] ] ?></td></tr>
				<tr><th><?= Yii::t('app', 'Bot')           ?>:</th><td><?= $signal['ubtname'] .' '.
					HTML::a(Yii::t('app', 'Update bot'), ['/membership/updatebot', 'id'=>$signal['ubtid']], ['class'=>'btn btn-info']) .' '.
					HTML::a(Yii::t('app', 'Add a bot'), ['/membership/addbot', 'id'=>$umbid], ['class'=>'btn btn-primary']) ?></td></tr>
				<tr><th><?= Yii::t('app', 'Bot active')    ?>:</th><td><?= $yesNos[ $signal['ubtactive'] ] ?></td></tr>
				<tr><th><?= Yii::t('app', '3Commas botid') ?>:</th><td><?= $signal['3cbotid'] ?></td></tr>
			</table>
		<?php endforeach; ?>

	<?php endforeach; ?>

<?php endforeach; ?>
		</div>
  </div>
</div>


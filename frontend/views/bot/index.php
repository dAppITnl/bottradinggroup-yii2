<?php

//use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos();
$userId = Yii::$app->user->id;
$userIdentity = Yii::$app->user->identity;

$signals = \frontend\models\Signal::getSignalsForUserBotsignal();

?>
<div class="site-index">
	<div class="body-content">

		<div class="row">
			<div class="col text-center">
				<h1><?= Yii::t('app', 'Your bots') ?></h1>
			</div>
		</div>

<?php if (empty($userbotsModel)) : ?>
		<div class="row">
			<div class="col text-center">
				<p><?= Yii::t('app', "You don't have any bots added.") ?></p>
			</div>
		</div>

<?php else: ?>

		<div class="row">
			<div class="col text-center">

  <?php $botnr = $signr = 0; $maxsig = [];
				foreach($userbotsModel as $nr => $userbot) :
					$botnr++; $signr = 0;
					$mbrId = $userbot->ubtumb->umbmbr_id;
					$maxsig[$mbrId] = $userbot->ubtumb->umb_maxsignals; ?>
				<p><table border="1">
					<tr><th><?= $botnr .'. '. Yii::t('app', 'Bot') ?>:</th><td><big><b><?= $userbot->ubt_name ?></b></big> <?=
									HTML::a(Yii::t('app', 'Update bot'), ['/bot/updatebot', 'id'=>$userbot->id], ['class'=>'btn btn-info'])
									.' '. HTML::a(Yii::t('app', 'Add a bot'), ['/bot/addbot', 'id'=>$userbot->ubtumb_id], ['class'=>'btn btn-primary']) ?></big></td></tr>
					<tr><th><?= Yii::t('app', 'Membership') ?>:</th><td><?= $userbot->ubtumb->umbmbr->mbr_title .' ('.Yii::t('app', 'of').' '. $userbot->ubtumb->umb_name .')'
									/*.' '. HTML::a(Yii::t('app', 'View'), ['/membership', 'id'=>$mbrId], ['class'=>'btn btn-primary'])*/ ?></td></tr>
					<tr><th><?= Yii::t('app', 'Active') ?>:</th><td><?= $yesNos[ $userbot->ubt_active ] ?></td></tr>
					<tr><th><?= Yii::t('app', 'Start/Stop') ?>:</th><td><?= $yesNos[ $userbot->ubt_userstartstop ] ?></td></tr>
					<!-- tr><th><?= Yii::t('app', '3Commas botId') ?>:</th><td><?= $userbot->ubt_3cbotid ?></td></tr -->
					<tr><th><?= Yii::t('app', '3Commas message') ?>:</th><td><?= $userbot->ubt_3cdealstartjson ?></td></tr>
					<tr><th><?= Yii::t('app', 'Remarks') ?>:</th><td><?= $userbot->ubt_remarks ?></td></tr>
					<tr><th><?= Yii::t('app', 'Created') ?>:</th><td><?= $userbot->ubt_createdt ?></td></tr>
					<tr><th><?= Yii::t('app', 'Updated') ?>:</th><td><?= $userbot->ubt_updatedt ?></td></tr>

		<?php if (count($userbot->botsignals) > 0) :
						foreach($userbot->botsignals as $botsignal) : $signr++; ?>
					<tr><th><?= $botnr.'.'.$signr.' '.Yii::t('app', 'Signal') . ' ('.$signr.'/'.$maxsig[$mbrId].')' ?>: </th><td><?= $signals[ $botsignal->bsgsig_id ] ?></ul>
						<?= HTML::a(Yii::t('app', 'Update signal'), ['/bot/updatebotsignal', 'id'=>$botsignal->id], ['class'=>'btn btn-info']) .' '.
												($signr < $maxsig[$mbrId] ? HTML::a(Yii::t('app', 'Add a signal'), ['/bot/addbotsignal', 'id'=>$userbot->id], ['class'=>'btn btn-primary'])
																					: '('.YII::t('app', 'Max nr of signals reached').')' ) ?>
					</td></tr>
			<?php endforeach;
					else: ?>
					<tr><th><?= $botnr.'. '.Yii::t('app', 'Signal') . ' ('.$signr.'/'.$maxsig[$mbrId].')' ?>: </th><td><?= Yii::t('app', 'No signal selected') .' '.
											($signr < $maxsig[$mbrId] ? HTML::a(Yii::t('app', 'Add a signal'), ['/bot/addbotsignal', 'id'=>$userbot->id], ['class'=>'btn btn-primary'])
																				: YII::t('app', 'and max nr of signals reached') ) ?></td></tr>
		<?php endif; ?>

				</table></p>
	<?php endforeach; ?>
			</div>
		</div>
<?php endif; ?>

	</div>
</div>

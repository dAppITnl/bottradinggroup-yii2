<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;
use frontend\models\Usermember;

$userpayStates = GeneralHelper::getUserpayStates();

$userId = Yii::$app->user->id;
$userIdentity = Yii::$app->user->identity;

$signals = \frontend\models\Signal::getSignalsForUserBotsignal();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();

$usermembersData = Usermember::getUsermembersOfUser($userModel->id, false, true, false);

?>
		<div class="row">
			<div class="col text-center">
        <p><?= Yii::t('app', 'Here is history and inactive data shown. For current and active situation {link:seehere}see here{/link}.', ['link:seehere'=>'<a href="/membership/index">', '/link'=>'</a>']) ?></p>
      </div>
    </div>

	<?php foreach($usermembersData as $nr => $usermember) : if (is_numeric($nr)) :
					$maxsignals = $usermember->umb_maxsignals;
					$state = GeneralHelper::showActiveInPeriod($usermember->umb_active, $usermember->umb_startdate, $usermember->umb_enddate); ?>
		<div class="row">
      <div class="col">
				<p><table class="table-100" border="1">
					<tr><th><?= Yii::t('app', 'Membership') ?>: </th><td><span class="table-title"><?= $usermember->umbmbr->mbr_title ?></span></td></tr>
					<tr><th><?= Yii::t('app', 'Period')     ?>: </th><td><?= GeneralHelper::showDateTime($usermember->umb_startdate,'dmY') . Yii::t('app', ' up to ') . GeneralHelper::showDateTime($usermember->umb_enddate,'dmY') ?></td></tr>
					<tr><th><?= Yii::t('app', 'Max signals')?>: </th><td><?= $maxsignals ?></td></tr>
					<tr><th><?= Yii::t('app', 'State')      ?>: </th><td><?= $state['message'] ?></td></tr>

		<?php if (count($usermember->userpays) > 0) :
						foreach($usermember->userpays as $userpay) : Yii::trace('** view history: userpay:'.print_r($userpay,true)); ?>
					<tr><th><?= /*Yii::t('app', 'Paid')*/ $userpayStates[ $userpay['upy_state'] ]       ?>: </th><td><?= GeneralHelper::showDateTime($userpay->upy_startdate,'dmy') . Yii::t('app', ' up to ') . GeneralHelper::showDateTime($userpay->upy_enddate,'dmy')
							.' ('. $userpay->upysymPay->sym_html . ' '. $userpay->upy_payamount
							.'; '. $pricelistPeriods[$userpay->upy_percode] .', ref='.$userpay->upy_payref.')' ?></td></tr>
			<?php endforeach;
					else: ?>
					<tr><th><?= Yii::t('app', 'Payment')       ?>: </th><td style="color:#ab0909;font-weight:600;"><?= Yii::t('app', 'Not paid') ?> <?= HTML::a( Yii::t('app', 'Pay'), ['/membership/pay', 'id'=>$usermember->id], ['class'=>'knop-2']) ?></td></tr>
		<?php endif; ?>

		<?php if ($state['active'] ) : ?>
			<?php if ($maxsignals > 0) : ?>
				<?php if (count($usermember->userbots) > 0) : $botnr = $signr = 0;
								foreach($usermember->userbots as $userbot) : $botnr++; ?>
					<tr><th><?= $botnr.' '.Yii::t('app', 'Bot') ?>: </th><td><?= $userbot->ubt_name ?>: <?= $userbot->ubt_3cbotid ?><br>
						<?= $userbot->ubt_3cdealstartjson ?><br>
								<?= HTML::a(Yii::t('app', 'Update bot'), ['/bot/updatebot', 'id'=>$userbot->id], ['class'=>'btn btn-info']) .' '.
										HTML::a(Yii::t('app', 'Add a bot'), ['/bot/addbot', 'id'=>$usermember->id], ['class'=>'btn btn-primary']) ?></td></tr>
						<?php if (count($userbot->botsignals) > 0) :
										foreach($userbot->botsignals as $botsignal) : $signr++; ?>
          <tr><th><?= $botnr.'.'.$signr.' '.Yii::t('app', 'Signal') ?>: </th><td><?= $signals[ $botsignal->bsgsig_id ] ?></ul>
						<?= HTML::a(Yii::t('app', 'Update signal'), ['/bot/updatebotsignal', 'id'=>$botsignal->id], ['class'=>'btn btn-info']) .' '.
								($signr < $maxsignals ? HTML::a(Yii::t('app', 'Add a signal'), ['/bot/addbotsignal', 'id'=>$userbot->id], ['class'=>'btn btn-primary'])
																			: '('.YII::t('app', 'Max nr of signals reached').')' ) ?>
					</td></tr>
							<?php endforeach;
									else: ?>
          <tr><th><?= $botnr.' '.Yii::t('app', 'Signal') ?>: </th><td><?= Yii::t('app', 'No signal selected') .' '.
											($signr < $maxsignals ? HTML::a(Yii::t('app', 'Add a signal'), ['/bot/addbotsignal', 'id'=>$userbot->id], ['class'=>'btn btn-primary'])
																						: YII::t('app', 'and max nr of signals reached') ) ?></td></tr>
						<?php endif; ?>
					<?php endforeach;
							else: ?>
					<tr><th><?= Yii::t('app', 'Bot') ?>: </th><td><?= Yii::t('app', 'No bot + signal given') .' '.
											HTML::a(Yii::t('app', 'Add a bot'), ['/bot/addbot', 'id'=>$usermember->id], ['class'=>'btn btn-primary']) ?></td></tr>
				<?php endif; ?>
			<?php else: ?>
					<tr><th><?= Yii::t('app', 'Bot & Signal')?>: </th><td><?= Yii::t('app', 'No bot & signals in this membership available'); ?></td></tr>
			<?php endif; ?>
		<?php endif; ?>

				</table></p>
			</div>
		</div>
	<?php endif; endforeach; ?>

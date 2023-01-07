<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;
use frontend\models\Usermember;
use frontend\models\Userpay;
use common\helpers\GeneralHelper;

//$userId = Yii::$app->user->id;
//$userIdentity = Yii::$app->user->identity;

//$signals = \frontend\models\Signal::getSignalsForUserBotsignal();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$yesNos = GeneralHelper::getYesNos();

$isDev = Yii::$app->user->identity->isDev();

$usermembersData = Usermember::getUsermembersOfUser($userModel->id, false, false, true);
Yii::trace('** view membership index -> usermembersData: '.print_r($usermembersData, true));


Url::remember(); // for add/update bot form returns

?>
<div class="site-index">
  <div class="body-content">

    <div class="row">
      <div class="col text-center">
        <h2><?= Yii::t('app', 'Active subscriptions') ?></h2>
      </div>
    </div>

		<div class="row">
			<div class="col text-center">
        <p><?= Yii::t('app', '{link:seehere}Click here{/link} for expired subscriptions', ['link:seehere'=>'<a href="/membership/history">', '/link'=>'</a>']) ?></p>
      </div>
    </div>

<?php if (empty($usermembersData)) : ?>

    <div class="row">
      <div class="col text-center">
        <p><h4><?= Yii::t('app', 'You do not have a current subscription') ?></h4></p>

        <p><?= HTML::a(Yii::t('app', 'Buy a membership subscription'), ['/membership/subscribe'], ['class'=>'btn btn-success']) ?></p>
      </div>
    </div>

<?php else: ?>
				$umbnr=0;
				foreach($usermembersData as $nr => $usermember) :
					Yii::trace('** index-current usermember:'.print_r($usermember,true));
					if (is_numeric($nr)) : $umbnr++; $upyperiod=explode('|', $usermember['upyperiod']); ?>
		<div class="row">
      <div class="col">
				<p><table class="table-100" border="1">
					<tr><th><?= $umbnr .': '. Yii::t('app', 'Membership')  ?>: </th><td valign="top"><span class="table-title"><?= $usermember['mbrtitle'] ?></span></td></tr>
					<tr><th><?=               Yii::t('app', 'Period')      ?>: </th><td><?= GeneralHelper::showDateTime($upyperiod[0],'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($upyperiod[1],'dmY') ?></td></tr>
					<tr><th><?=               Yii::t('app', 'Max signals') ?>: </th><td><?= ($usermember['umb_maxsignals']==0 ? Yii::t('app', 'unlimited') : $usermember['umb_maxsignals']) ?></td></tr>

      <?php $userpays = Userpay::getPaymentsOfUsermember($usermember['id'], true);
            if (!empty($userpays)) :
              foreach($userpays as $userpay) : ?>
          <tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= GeneralHelper::showDateTime($userpay['upy_startdate'],'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($userpay['upy_enddate'],'dmY')
            .' for '. GeneralHelper::getPricelistPeriods()[$userpay['upy_percode']] .' a '. $userpay['fiatcode'].' '.$userpay['upy_payamount']
            . (!empty($userpay['upy_payamount']) ? ' as ' . $userpay['upy_cryptoamount'] .' '. $userpay['cryptocode'] : '')
            .', ref='. HTML::a($userpay['upy_payref'], ['/userpay/view?id='.$userpay['id']]) .', maxSignals='.$userpay['upy_maxsignals'] ?></td></tr>
        <?php endforeach;
            else: ?>
          <tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= Yii::t('app', 'No pay data') ?></td></tr>
      <?php endif; ?>


					<tr><th valign="top"><?= Yii::t('app', 'Bot') ?></th><td valign="top"><?= HTML::a('<i class="fa fa-plus"></i> '.Yii::t('app', 'Add a bot'), ['/bot/addbot', 'id'=>$usermember['id']], ['class'=>'btn btn-primary']); ?>
			<?php if ($usermember['botcount']==0) : ?>
							<?= ' '. Yii::t('app', 'No bot available') ?>
			<?php else:
							$botnr=0; ?>
						<p><table class="table-100" border="1">
							<tr><th>#</th> <th><?= Yii::t('app', 'Action') ?></th> <th><?= Yii::t('app', 'Active') ?></th> <th><?= Yii::t('app', 'Start/Stop') ?></th> <th><?= Yii::t('app', 'Name') ?></th> <th><?= Yii::t('app', 'ID') ?></th> <th><?= Yii::t('app', 'Signal(s)') ?></th></tr>
				<?php if (!empty($userbotData) && !empty($userbotData[ $umbid ])) :
								Yii::trace('** index-currents umbid='.$umbid.' userbotData: '.print_r($userbotData[$umbid],true));
								foreach($userbotData[ $umbid ] as $ubtid => $userbot) :
									if (is_numeric($ubtid)) : $botnr++; ?>
							<tr><td valign="top"><?= $botnr ?></td>
								<td valign="top">
									<?= HTML::a(/*Yii::t('app', 'Update bot')*/'<i class="fa fa-pen-to-square"></i>', ['/bot/updatebot', 'id' => $ubtid], ['class'=>'btn btn-info']) ?><br>
								</td>
								<td valign="top"><?= $yesNos[ $userbot['ubt_active'] ]; ?></td>
								<td valign="top"><?= $yesNos[ $userbot['ubt_userstartstop'] ]; ?></td>
								<td valign="top"><?= $userbot['ubt_name'] ?></td>
								<td valign="top"><?= $userbot['ubt_3cbotid'] ?></td>
								<td valign="top">
							<?php $signalData = json_decode($userbot['signals'], true);
										if (!empty($signalData)) : ?>
								<?php foreach($signalData as $signal) : ?>
									<?= $yesNos[ $signal['active'] ] .' '. $signal['name'] ?><br>
								<?php endforeach;
										else: echo Yii::t('app', 'No signal assigned');
										endif; ?>
								</td></tr>
						<?php	endif;
								endforeach;
							endif; ?>
						</table></p>
			<?php	endif; ?>
					</td></tr>
				</table></p>
			</div>
		</div>
		<?php endif;
				endforeach;
		  endif; ?>

  </div>
</div>

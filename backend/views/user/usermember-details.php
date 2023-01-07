<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use common\helpers\GeneralHelper;

$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$yesNos = GeneralHelper::getYesNos(false);

//$isDev = Yii::$app->user->identity->isDev();

//Yii::trace('** view membership index usrid='.(\Yii::$app->user->id).' -> usermembersData: '.print_r($usermembersData, true));
//Yii::trace('** view membership index umbids='.$umbids.' => userpaysData: '.print_r($userpaysData, true));
//Yii::trace('** view membership index umbids='.$umbids.' => userbotsData: '.print_r($userbotsData, true));

//Url::remember(); // for add/update bot form returns
?>
	<?php	$umbnr=0;
				foreach($usermembersData as $nr => $usermember) :
					Yii::trace('** index-current usermember:'.print_r($usermember,true));
					if (is_numeric($nr)) : $umbnr++; $upyperiod=explode('|', $usermember['upyperiod']); ?>
		<div class="row">
      <div class="col">
				<p><table class="table-100" border="1">
					<tr><th><?= $umbnr .': '. Yii::t('app', 'Membership')   ?>: </th><td><?= HTML::a($usermember['mbrtitle'], ['membership/view?id='.$usermember['mbrid']], ['class'=>'table-title']) ?></td></tr>
					<tr><th><?=               Yii::t('app', 'Usermember')   ?>: </th><td><?= HTML::a($usermember['umbname'],  ['usermember/view?id='.$usermember['id']]) ?></td></tr>
					<tr><th><?=               Yii::t('app', 'Total period') ?>: </th><td><?= GeneralHelper::showDateTime($upyperiod[0],'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($upyperiod[1],'dmY') ?></td></tr>



      <?php if (!empty($userpaysData[ $usermember['id'] ])) :
              foreach($userpaysData[ $usermember['id'] ]  as $nr => $userpay) : if (is_numeric($nr)) : ?>
          <tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= Yii::t('app', '{startdate} up to {enddate} for {period} a {fiat} {crypto}. {maxsignals} signals. Ref={ref}', [
						'startdate'=>GeneralHelper::showDateTime($userpay['upy_startdate'],'dmY'), 'enddate'=>GeneralHelper::showDateTime($userpay['upy_enddate'],'dmY'),
						'period'=>GeneralHelper::getPricelistPeriods()[ $userpay['upy_percode'] ],
						'fiat'=>$userpay['upy_payamount'].' '.$userpay['fiatcode'],
						'crypto'=>(!empty($userpay['upy_cryptoamount']) ? $userpay['upy_cryptoamount'].' '.$userpay['cryptocode'] : ''),
						'maxsignals'=>(($userpay['upy_maxsignals']==0) ? Yii::t('app', 'Unlimited') : Yii::t('app', 'Max').' '.$userpay['upy_maxsignals']),
						'ref'=>HTML::a($userpay['upy_payref'], ['/userpay/view?id='.$userpay['id']])
					]) ?></td></tr>
        <?php endif; endforeach;
            else : ?>
          <tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= Yii::t('app', 'No pay data') ?></td></tr>
      <?php endif; ?>



					<tr><th valign="top"><?= Yii::t('app', 'Bot') ?></th><td valign="top"><?= HTML::a('<i class="fa fa-plus"></i> '.Yii::t('app', 'Add a bot'), ['/user/addbot', 'id'=>$usermember['id']]); ?>
			<?php if (!empty($userbotsData[ $usermember['id'] ])) : $botnr=0; ?>
						<p><table class="table-100" border="1">
							<tr><th>#</th> <th><?= Yii::t('app', 'Action') ?></th> <th><?= Yii::t('app', 'Active') ?></th> <th><?= Yii::t('app', 'Start/Stop') ?></th> <th><?= Yii::t('app', 'Name') ?></th> <th><?= Yii::t('app', '3Commas-ID') ?></th> <th><?= Yii::t('app', 'Signal(s)') ?></th></tr>
				<?php Yii::trace('** index-currents umbid='.$umbid.' userbotData: '.print_r($userbotData[$umbid],true));
							foreach($userbotsData[ $usermember['id'] ] as $nr => $userbot) : if (is_numeric($nr)) : $botnr++; ?>
							<tr><td valign="top"><?= $botnr ?></td>
								<td valign="top">
									<?= HTML::a(/*Yii::t('app', 'Update bot')*/'Update', ['/user/updatebot', 'id' => $userbot['id']]) ?>
								</td>
								<td valign="top"><?= $yesNos[ $userbot['ubt_active'] ]; ?></td>
								<td valign="top"><?= $yesNos[ $userbot['ubt_userstartstop'] ]; ?></td>
								<td valign="top"><?= $userbot['ubt_name'] ?></td>
								<td valign="top"><?= $userbot['ubt_3cbotid'] ?></td>
								<td valign="top">
					<?php $signalData = json_decode($userbot['signals'], true);
								if (!empty($signalData)) : ?>
						<?php foreach($signalData as $signal) : ?>
								<?= HTML::a($yesNos[ $signal['active'] ], ['/user/updatebotsignal', 'id'=>$signal['bsgid'] ])
									.' | '. HTML::a('Log', ['/signallog/botdetail', 'id'=>$signal['bsgid'] ])
									.' | '. HTML::a($signal['name'], ['/signal/view', 'id'=>$signal['sigid'] ]) ?><br>
						<?php endforeach;
								else :
									echo Yii::t('app', 'No signal assigned');
								endif; ?>
								</td></tr>
				<?php endif; endforeach; ?>
						</table></p>
			<?php else: ?>
				<?= ' '. Yii::t('app', 'No bot available') ?>
			<?php	endif; ?>
					</td></tr>
				</table></p>
			</div>
		</div>
		<?php endif;
				endforeach; ?>

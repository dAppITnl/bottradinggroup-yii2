<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

?>
<div class="site-index">
  <div class="body-content">

<?php if (empty($usermembersModel)) : ?>
		<div class="row">
      <div class="col text-center">
        <h2><?= HTML::a(Yii::t('app', 'Select membership level').'..', ['/membership/subscribe'], ['class'=>'']) ?></h2>
			</div>
		</div>
<?php else: ?>
		<div class="row">
      <div class="col text-center">
				<h2><?= Yii::t('app', 'Your membership') ?></h2>
			</div>
		</div>

	<?php foreach($usermembersModel as $nr => $usermember) : ?>
		<div class="row">
      <div class="col">
				<p><table border="0">
					<tr><th><?= Yii::t('app', 'Membership') ?>: </th><td><h3><?= $usermember->umbmbr->mbr_title ?></h3></td></tr>
					<tr><th><?= Yii::t('app', 'Id')  ?>: </th><td><?= $usermember->id ?></td></tr>
					<tr><th><?= Yii::t('app', 'Period')     ?>: </th><td><?= GeneralHelper::showDateTime($usermember->umb_startdate,'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($usermember->umb_enddate,'dmY') ?></td></tr>
					<tr><th><?= Yii::t('app', 'State')      ?>: </th><td><?= GeneralHelper::showActiveInPeriod($usermember->umb_active, $usermember->umb_startdate, $usermember->umb_enddate) ?></td></tr>

		<?php if (count($usermember->userpays) > 0) :
						foreach($usermember->userpays as $userpay) : ?>
					<tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= GeneralHelper::showDateTime($userpay->upy_startdate,'dmy') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($userpay->upy_enddate,'dmy') .' ('. GeneralHelper::getPricelistPeriods()[$userpay->upy_percode] .', ref='.$userpay->upy_payref.')' ?></td></tr>
			<?php endforeach;
					else: ?>
					<tr><th><?= Yii::t('app', 'Paid')       ?>: </th><td><?= Yii::t('app', 'Not paid') ?> <?= HTML::a( Yii::t('app', 'Pay'), ['/membership/pay', 'id'=>$usermember->id], ['class'=>'btn btn-primary']) ?></td></tr>
		<?php endif; ?>

		<?php if (count($usermember->userbots) > 0) :
						foreach($usermember->userbots as $userbot) : ?>
					<tr><th><?= Yii::t('app', 'Bot')        ?>: </th><td><?= $userbot->ubt_name ?>: <?= $userbot->ubt_3cbotid ?><br>
						<?= $userbot->ubt_3cdealstartjson ?><br><?= HTML::a(Yii::t('app', 'Update bot'), ['/membership/updatebot', 'id'=>$userbot->id], ['class'=>'btn btn-info']) ?></td></tr>
				<?php if (count($userbot->botsignals) > 0) :
								foreach($userbot->botsignals as $botsignal) : ?>
          <tr><th><?= Yii::t('app', 'Signal')     ?>: </th><td><?= $botsignal->bsg_name ?><br><?= HTML::a(Yii::t('app', 'Update signal'), ['/membership/updatebotsignal', 'id'=>$botsignal->id], ['class'=>'btn btn-info']) ?>
</td></tr>
					<?php endforeach;
							else: ?>
          <tr><th><?= Yii::t('app', 'Signal')     ?>: </th><td><?= Yii::t('app', 'No signal selected') ?> <?= HTML::a(Yii::t('app', 'Add a signal'), ['/membership/addbotsignal', 'id'=>$userbot->id], ['class'=>'btn btn-primary']) ?></td></tr>
				<?php endif; ?>
			<?php endforeach;
					else: ?>
					<tr><th><?= Yii::t('app', 'Bot')        ?>: </th><td><?= Yii::t('app', 'No bot + signal given') ?> <?= HTML::a(Yii::t('app', 'Enter your bot'), ['/membership/addbot', 'id'=>$usermember->id], ['class'=>'btn btn-primary']) ?></td></tr>
		<?php endif; ?>

				</table></p>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

	</div>
</div>

<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use backend\models\Userpay;
use backend\models\Userbot;
use common\helpers\GeneralHelper;
use backend\models\Usermember;

$yesNos = GeneralHelper::getYesNos(false);

$usermembersData = Usermember::getUsermembersOfUser($userModel->id, false, true, true);
Yii::trace('** view userdetail usermembersData: '.print_r($usermembersData, true));

$this->title = Yii::t('models', 'User details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$userModel->id, 'url' => ['view', 'id' => $userModel->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Details');
?>
<div class="site-index">
  <div class="body-content">

		<div class="row">
      <div class="col text-center">
        <h2>User: <?= HTML::a($userModel->username, ['/user/view?id='.$userModel->id]) ?></h2>
      </div>
    </div>

<?php if (empty($usermembersData)) : ?>
		<div class="row">
      <div class="col text-center">
        <?= Yii::t('app', 'No usermember available') ?></h2>
			</div>
		</div>
<?php else: ?>
	<?php foreach($usermembersData as $nr => $usermember) : $upyperiod=explode('|', $usermember['upyperiod']); ?>
		<div class="row">
      <div class="col">
				<p><table class="table">
					<tr><th><?= Yii::t('app', 'Membership') ?>: </th><td><h3><?= HTML::a($usermember['mbrtitle'], ['/usermember/view?id='.$usermember['id']]) ?></h3></td></tr>
					<tr><th><?= Yii::t('app', 'Period')     ?>: </th><td><?= GeneralHelper::showDateTime($upyperiod[0],'dmY') . Yii::t('app', ' up to ') . GeneralHelper::showDateTime($upyperiod[1],'dmY') ?></td></tr>
					<tr><th><?= Yii::t('app', 'State')      ?>: </th><td><?php $active = GeneralHelper::showActiveInPeriod($usermember['umb_active'], $upyperiod[0], $upyperiod[1]);
						echo $yesNos[ (($active['active']=='true') ? 1 : 0) ] .', '. $active['message']; ?></td></tr>

		<?php $userpays = Userpay::getPaymentsOfUsermember($usermember['id'], true);  //Userpay::find()->where(['upyumb_id'=>$usermember['id'], 'upy_deletedat'=>0, 'upy_startdate' ])->all();
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

		<?php $userbots = Userbot::find()->where(['ubtumb_id'=>$usermember['id'], 'ubt_deletedat'=>0])->all();
					if (count($userbots) > 0) : $botnr=0;
						foreach($userbots as $userbot) : $botnr++; ?>
					<tr><th><?= $botnr.'/'.(count($usermember->userbots)) .' '. Yii::t('app', 'Bot')        ?>: </th><td><?= HTML::a($userbot->ubt_name, ['/userbot/view?id='.$userbot->id]) ?>: <?= $userbot->ubt_3cbotid ?><br><?= $userbot->ubt_3cdealstartjson ?></td></tr>
				<?php if (count($userbot->botsignals) > 0) : $signr=0;
								foreach($userbot->botsignals as $botsignal) : $signr++; ?>
          <tr><th><?= $botnr.'.'.$signr .' '. Yii::t('app', 'Signal')     ?>: </th><td><?= HTML::a($botsignal->bsgsig->sig_name , ['/botsignal/view?id='.$botsignal->id]) .': '. ($botsignal->bsg_active ? Yii::t('app', 'Active') : Yii::t('app', 'Inactive'))  ?>
</td></tr>
					<?php endforeach;
							else: ?>
          <tr><th><?= $botnr.'.'.$signr .' '. Yii::t('app', 'Signal')     ?>: </th><td><?= Yii::t('app', 'No signal selected') ?></td></tr>
				<?php endif; ?>
			<?php endforeach;
					else: ?>
					<tr><th><?= Yii::t('app', 'Bot')        ?>: </th><td><?= Yii::t('app', 'No bot + signal given') ?></td></tr>
		<?php endif; ?>

				</table></p>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>

	</div>
</div>

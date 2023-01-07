<?php

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;

use common\helpers\GeneralHelper;

//$userId = Yii::$app->user->id;
//$userIdentity = Yii::$app->user->identity;

//$signals = \frontend\models\Signal::getSignalsForUserBotsignal();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$yesNos = GeneralHelper::getYesNos();

Url::remember(); // for add/update bot form returns

?>

		<div class="row">
			<div class="col text-center">
        <p><?= Yii::t('app', 'Here we show only current and active bots with signals. For a history {link:seehere}see here{/link}.', ['link:seehere'=>'<a href="/membership/history">', '/link'=>'</a>']) ?></p>
      </div>
    </div>

<?php $umbnr=0;
			foreach($usermemberData as $umbid => $usermember) :
				if (is_numeric($umbid)) : $umbnr++; ?>
		<div class="row">
      <div class="col">
				<p><table class="table-100" border="1">
					<tr><th><?= $umbnr .': '. Yii::t('app', 'Membership')       ?>: </th><td valign="top"><span class="table-title"><?= $usermember['mbr_title'] ?></span><?= ' ('.$umbid.')' ?></td></tr>
					<tr><th><?= Yii::t('app', 'Last paid period') ?>: </th><td><?=  GeneralHelper::showDateTime($usermember['upy_startdate'],'dmY')
																																					.' '. Yii::t('app', 'up to') .' '.
																																					GeneralHelper::showDateTime($usermember['upy_enddate'],'dmY')
																																					.' ('. $pricelistPeriods[ $usermember['upy_percode'] ] .')' ?></td></tr>
					<tr><th><?= Yii::t('app', 'Max signals')?>: </th><td><?= ($usermember['umb_maxsignals']==0 ? Yii::t('app', 'unlimited') : $usermember['umb_maxsignals']) ?></td></tr>
					<tr><th valign="top"><?= Yii::t('app', 'Bot') ?></th><td valign="top"><?= HTML::a('<i class="fa fa-plus"></i> '.Yii::t('app', 'Add a bot'), ['/bot/addbot', 'id'=>$usermember['id']], ['class'=>'btn btn-primary']) ?>
		<?php if ($usermember['botcount']==0) : ?>
						<?= ' '. Yii::t('app', 'No bot available') ?>
		<?php else:
						$botnr=0; ?>
						<p><table class="table-100" border="1">
							<tr><th>#</th> <th><?= Yii::t('app', 'Action') ?></th> <th><?= Yii::t('app', 'Active') ?></th> <th><?= Yii::t('app', 'Name') ?></th> <th><?= Yii::t('app', 'ID') ?></th> <th><?= Yii::t('app', 'Signal(s)') ?></th></tr>
			<?php if (!empty($userbotData) && !empty($userbotData[ $umbid ])) :
							Yii::trace('** index-currents umbid='.$umbid.' userbotData: '.print_r($userbotData[$umbid],true));
							foreach($userbotData[ $umbid ] as $ubtid => $userbot) :
								if (is_numeric($ubtid)) : $botnr++; ?>
							<tr><td valign="top"><?= $botnr ?></td>
								<td valign="top">
									<?= HTML::a(/*Yii::t('app', 'Update bot')*/'<i class="fa fa-pen-to-square"></i>', ['/bot/updatebot', 'id' => $ubtid], ['class'=>'btn btn-info']) ?><br>
								</td>
								<td valign="top"><?= $yesNos[ $userbot['ubt_active'] ]; ?></td>
								<td valign="top"><?= $userbot['ubt_name'] .' ('.$ubtid.')' ?></td>
								<td valign="top"><?= $userbot['ubt_3cbotid'] ?></td>
								<td valign="top"><?= '' // HTML::a(Yii::t('app', 'Add signal'), ['/bot/addbotsignal', 'id'=>$ubtid], ['class'=>'btn btn-primary']) ?>
									<?php $signalData = json_decode($userbot['signals'], true);
												if (!empty($signalData)) : ?>
										<?php foreach($signalData as $signal) : ?>
									<!-- span class="text-nowrap" -->
										<?= '' //Html::a('<i class="fa fa-trash"></i>', ['/bot/delbotsignal', 'id'=>$signal['bsgid']], ['class'=>'btn btn-danger',	'onclick'=>'return confirm("'.Yii::t('app','Are you sure you want to remove this signal?').'")']) ?>
										<!-- span class="custom-control custom-checkbox" -->
											<!-- input type="checkbox" id="sig-<?= $signal['bsgid']?>" value="1" class="custom-control-input"  onchange="updateSignalActive(<?= $signal['bsgid']?>,this.checked)" <?= (!empty($signal['active']) ? 'checked':'') ?> -->
											<!-- label for="sig-<?= $signal['bsgid']?>" class="custom-control-label" --><?= ' '. $signal['name'] ?><!-- /label -->
										<!-- /span -->
									<!-- /span --><br>
										<?php endforeach;
												else : echo Yii::t('app', 'No signal assigned');
											endif; ?>
								</td></tr>
			<?php 		endif;
							endforeach;
						endif; ?>
						</table></p>
		<?php	endif; ?>
					</td></tr>
				</table></p>
			</div>
		</div>
	<?php endif;
			endforeach; ?>


<?php JSRegister::begin([
	'position' => \yii\web\View::POS_BEGIN
]); ?>
<script>
function updateSignalActive(id=0,checked=0) {
  try {
    console.log('change id='+id+' checked='+(checked?'1':'0'));
    if (id>0) {
      $.post(
        '/bot/updatesignalactive',
        {'id':id,'checked':(checked?'true':'false')}, (response) => {
          var data=JSON.parse(response);
          if (data.ok == 'true') {
						//
          } else {
						//
          }
        }
      );
    }
  } catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
  }
}
</script>
<?php JSRegister::end(); ?>


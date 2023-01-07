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

?>
  <?php $upyperiod = explode('|', $usermember['upyperiod']); ?>
  <div class="row">
    <div class="col">
      <p><table class="table-100" border="1">
        <tr><th><?= Yii::t('app', 'Membership')   ?>: </th><td><?= HTML::a($usermember['mbrtitle'], ['membership/view?id='.$usermember['mbrid']], ['class'=>'table-title']) ?></td></tr>
        <tr><th><?= Yii::t('app', 'Usermember')   ?>: </th><td><?= HTML::a($usermember['umbname'], ['usermember/view?id='.$usermember['umbid']]) ?></td></tr>
        <tr><th><?= Yii::t('app', 'Total period') ?>: </th><td><?= GeneralHelper::showDateTime($upyperiod[0],'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($upyperiod[1],'dmY') ?></td></tr>
				<tr><th><?= Yii::t('app', '3Commas-ID')   ?>: </th><td><?= $usermember['3cbotid'] ?></td></tr>
			</table></p>
		</div>
	</div>

  <div class="row">
    <div class="col">
      <h2><?= Yii::t('app', 'Log details of {username}', ['username'=> Html::a($usermember['username'], ['/user/view?id='.$usermember['usrid']]) ]) ?></h2>
      <p><?= Html::a(Yii::t('app', 'Userdetail'), ['/user/userdetail', 'id'=>$usermember['usrid'] ]) ?></p>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <p><table class="table-100" border="1">
				<tr><th>#</th> <th><?= Yii::t('app', 'Alert') ?></th> <th><?= Yii::t('app', 'First') ?></th> <th><?= Yii::t('app', 'Last') ?></th> <th><?= Yii::t('app', 'Count') ?></th></tr>
				<?php foreach($logdetails as $nr => $logdetail) : ?>
				<tr><td align="right"><?= ($nr+1) ?></td>
					<td><?= $logdetail['alertmsg'] ?></td>
					<td><?= GeneralHelper::showDateTime($logdetail['mindate'], 'dmyhis') ?></td>
					<td><?= GeneralHelper::showDateTime($logdetail['maxdate'], 'dmyhis') ?></td>
					<td align="right"><?= $logdetail['count'] ?></td>
				</tr>
				<?php endforeach; ?>
			</table></p>
		</div>
	</div>


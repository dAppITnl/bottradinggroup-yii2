<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\GeneralHelper;

$perCodes = GeneralHelper::getPricelistPeriods();

/* @var $this yii\web\View */

$this->title = 'Botsignals Admin site';

$mbrTotal = 0;
foreach($membershipData as $mbrid => $mbrData) {
	$mbrTotal += $mbrData['umbcount'];
}

$yearMonthOptions = [];
$months = GeneralHelper::getMonths(false);
$year=2021; $month=12; $ym=$year.$month;
while ($ym <= date('Ym')) {
	$yearMonthOptions[$ym] = $year .' - '. $months[ $month ];
	if ($month<12) $month++; else { $month=1; $year++; }
	$ym = ''.$year.(($month<10) ? '0':'').$month;
}
if (empty($yearmonth)) $yearmonth="202112";

Yii::trace('** view index statisticReportData: '.print_r($statisticReportData, true));
$report = $statisticReportData['report'];

?>
<div class="site-index">
	<div class="body-content">
		<div class="jumbotron text-center bg-transparent">
			<h1 class="display-4"><?= $this->title ?></h1>
			<p class="lead">Botsignalen (en meer) backend tbv volledige adminstratie en beheer.</p>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-12 col-md-12">
					<h2><?= Yii::t('app', 'Current Memberships') ?>:</h2>
					<?= Yii::t('app', 'Total') ?>: <?= $mbrTotal ?>
					<p><?php if (!empty($membershipData)) : ?>
						<table class="table">
							<tr>
								<th><?= Yii::t('app', 'Group:Order') ?></th>
								<th><?= Yii::t('app', 'Language') ?></th>
								<th><?= Yii::t('app', 'Code') ?></th>
								<th><?= Yii::t('app', 'Title') ?></th>
								<th><?= Yii::t('app', '#Usermembers') ?><br><?= Yii::t('app', 'Paid / Free / Total') ?></th>
							</tr>
							<?php foreach($membershipData as $mbrid => $mbrData) : ?>
							<tr>
								<td><?= $mbrData['mbr_groupnr'] .':'. $mbrData['mbr_order'] ?></td>
								<td><?= $mbrData['mbr_language'] ?></td>
								<td><?= $mbrData['mbr_code'] ?></td>
								<td><?= $mbrData['mbr_title'] ?></td>
								<td><?= $mbrData['umbcountpaid'].' / '.$mbrData['umbcountfree'].' / '.($mbrData['umbcountpaid']+$mbrData['umbcountfree']) ?></td>
							</tr>
							<?php endforeach; ?>
						</table>
						<?php else : ?>
							<?= Yii::t('app', 'No membership data available.') ?>
						<?php endif; ?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-12 col-md-12">
					<h2><?= Yii::t('app', 'Statistic Report') ?></h2>
					<?= Html::beginForm(['/site/index'], 'POST', ['enctype' => 'multipart/form-data']) ?>
						<?= Html::dropDownList('yearmonth', $yearmonth, $yearMonthOptions, ['id'=>'yearmonth']) ?>
						<?= Html::submitButton(Yii::t('app', 'Refresh'), ['class' => 'submit'])?>
					<?= Html::endForm() ?>
				</div>
			</div>

			<?php if (!empty($report)) : ?>
			<div class="row mt-2">
				<div class="col-12 col-md-12">
					<?= Yii::t('app', 'Number of active subscriptions') .': '. $report['totalmbrs'] ?>
				</div>
			</div>

			<div class="row">
  	    <div class="col-6 col-md-6">
					<h3><?= Yii::t('app', 'Memberships') ?>:</h3>
					<table class="table">
						<tr>
							<th><?= Yii::t('app', 'Membership') ?></th>
							<th><?= Yii::t('app', 'Count') ?></th>
						</tr>
						<?php foreach($report['mbr'] as $nr => $mbr) : ?>
						<tr>
							<td><?= $mbr['title'] ?></td>
							<td><?= $mbr['totalmbr'] ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
				<div class="col-6 col-md-6">
					<h3><?= Yii::t('app', 'Periods') ?>:</h3>
					<table class="table">
						<tr>
							<th><?= Yii::t('app', 'Periods') ?></th>
							<th><?= Yii::t('app', 'Count') ?></th>
						</tr>
						<?php foreach($report['percode'] as $percode => $totalpercode) : ?>
						<tr>
							<td><?= $perCodes[ $percode ] ?></td>
							<td><?= $totalpercode ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>

			<div class="row">
				<div class="col-12 col-md-12">
					 <h3><?= Yii::t('app', 'Amounts') ?>:</h3>
					<table class="table">
						<tr>
							<th><?= Yii::t('app', 'FIAT') ?></th>
							<th><?= Yii::t('app', 'FIAT amount') ?></th>
							<th><?= Yii::t('app', 'Crypto') ?></th>
							<th><?= Yii::t('app', 'Crypto amount') ?></th>
						</tr>
						<?php foreach($report['amount'] as $nr => $amount) : ?>
						<tr>
							<td><?= $amount['fiatsymbol'] ?></td>
							<td><?= $amount['fiattotal'] ?></td>
							<td><?= $amount['cryptosymbol'] ?></td>
							<td><?= $amount['cryptototal'] ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div>

			<?php else : ?>
			<div class="row">
				<div class="col-12 col-md-12">
					<?= Yii::t('app', 'No statistic data available, please make a period selection.') ?>
				</div>
			</div>
			<?php endif; ?>

    	<!-- div class="row">
      	<div class="col">
        	<h2></h2>
	        <p></p>
  	    </div>
    	</div -->
		</div>
  </div>
</div>

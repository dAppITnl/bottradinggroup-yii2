<?php

use yii\helpers\Url;
use yii\helpers\Html;

//use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;

use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use frontend\models\Usermember;
use frontend\models\Userpay;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$actionIsUpdate = !empty($userbotModel->id);

$tierName = Html::encode($usermemberModel->umbmbr->mbr_title);

//$usermemberships4User = GeneralHelper::getUsermembers4User( \Yii::$app->user->id, true );
//Yii::trace('** bot_form select: '.print_r($usermemberships4User,true));

Yii::trace('** view user-details-addbot_form usermemberData: '.print_r($usermemberData, true));
$umbid = array_key_first($usermemberData);
$usermember = $usermemberData[$umbid];
$upyperiod=explode('|', $usermember['upyperiod']);

$userpayData = Userpay::getCurrentUserpayOfUsermember( $umbid );
$maxSignals = 1 * $userpayData['upy_maxsignals']; //$usermemberModel->umb_maxsignals;
$usedSignals = 1 * $signalCounts[$umbid];
$maxText = (($maxSignals > 0) ? $maxSignals : Yii::t('app', 'unlimited'));

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/

Url::remember(); // for add/update bot form returns

?>
<div class="userbot">
	<h2><?= Yii::t('app', 'Membership') ?></h2>
	<div class="row">
		<div class="col">
			<p><table class="table-100" border="1">
			<tr><th><?= Yii::t('app', 'User')         ?>: </th><td><?= Html::a($usermember['username'], ['user/view?id='.$usermember['usrid']]) .' ('. HTML::mailto($usermember['email'], $usermember['username']) .')' ?></td></tr>
			<tr><th><?= Yii::t('app', 'Usermember')   ?>: </th><td><?= Html::a($usermember['umbname'], ['usermember/view?id='.$usermember['id']]) ?></td></tr>
			<tr><th><?= Yii::t('app', 'Membership')   ?>: </th><td><?= Html::a($usermember['mbrtitle'], ['membership/view?id='.$usermember['mbrid']]) ?></td></tr>
			<tr><th><?= Yii::t('app', 'Total period') ?>: </th><td><?= GeneralHelper::showDateTime($upyperiod[0],'dmY') . Yii::t('app', ' until ') . GeneralHelper::showDateTime($upyperiod[1],'dmY') ?></td></tr>
			</table></p>
		</div>
	</div>

	<p><hr></p>

  <h1><?= ($actionIsUpdate
					? Yii::t('app', 'Update bot') //Yii::t('app', "Update your bot of your plan<br>'{tierName}'.", ['tierName' => $tierName])
					: Yii::t('app', 'Add bot') /* Yii::t('app', "Add your 3Commas bot to your plan<br>'{tierName}'.", ['tierName' => $tierName])*/) ?></h1>

	<div class="userbot-form">
  	<?php $form = ActiveForm::begin([
			'id' => 'Userbot',
			'type' => ActiveForm::TYPE_VERTICAL, //'layout' => 'default',
			'enableClientValidation' => true,
			'errorSummaryCssClass' => 'error-summary alert alert-error',
			'formConfig' => ['showHints' => true],

			/*'layout' => 'horizontal',
  	  'enableClientValidation' => true,
    	'errorSummaryCssClass' => 'error-summary alert alert-danger',
	    'fieldConfig' => [
  	    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
    	  'horizontalCssClasses' => [
      	  'label' => 'col-sm-2',
        	#'offset' => 'col-sm-offset-4',
	        'wrapper' => 'col-sm-8',
  	      'error' => '',
    	    'hint' => '',
      	],
	    ],*/
  	]); ?>
		<?= $form->field($userbotModel, 'ubtumb_id')->hiddenInput(['value' => $usermemberModel->id])->label(false) ?>

	  <div class="row">
			<div class="col-md-5"><?= $form->field($userbotModel, 'ubt_active')->checkbox(['custom' => true, 'switch' => true/*'label' =>''*/]); ?><!-- /div -->
			<!-- div class="col-md-3" --><?= $form->field($userbotModel, 'ubt_userstartstop')->checkbox(['custom' => true, 'switch' => true/*'label' =>''*/]); ?></div>
			<div class="col-md-7"><?= $form->field($userbotModel, 'ubt_name')->textInput(['maxlength' => true, ]) ?></div>
		</div>
		<div class="row">
			<div class="col-md-12"><?= $form->field($userbotModel, 'ubt_3cdealstartjson')->textarea(['rows' => 3, ]) ?></div>
		</div>
		<div class="row">
			<div class="col-md-12"><?= $form->field($userbotModel, 'ubt_remarks')->textarea(['rows' => 3, ]) ?></div>
		</div>
		<div class="row">
    	<div class="col-md-12"><?php echo $form->errorSummary($userbotModel); ?></div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . ($actionIsUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Add')),
        	['id' => 'save-' . $userbotModel->formName(),  'class' => 'btn btn-success']); ?>
				<?= ((!$userbotModel->isNewRecord && empty($botsignalModel)) ? Html::a(Yii::t('app', 'Delete'), ['/bot/deletebot', 'id'=>$userbotModel->id ],
					['class'=>'btn btn-danger ml-1', 'onclick'=>'return confirm("'.Yii::t('app','Are you sure you want to remove this bot?').'")']) : '') ?>
				<?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->getReferrer(), ['class'=>'btn btn-primary ml-1']) ?>
			</div>
		</div>
  	<?php ActiveForm::end(); ?>
	</div>
</div>

<p><hr></p>

<?php if (!$userbotModel->isNewRecord) : ?>
<div class="botsignal">
	<h2><?= Yii::t('app', 'Bot signals'); ?></h2>

	<?php if (($maxSignals == 0) || ($usedSignals < $maxSignals)) : ?>
		<?= HTML::a('<i class="fa fa-plus"></i> '.Yii::t('app', 'Add signal'), ['/user/addbotsignal', 'id'=>$userbotModel->id])
				.' ('. Yii::t('app', 'Used {nr}/{max} signals in this membership', ['nr'=>$usedSignals, 'max'=>$maxText ]) .')' ?>
	<?php else : ?>
		<p class="mt-2"><?= Yii::t('app', 'Max ({usedsignals}/{maxtext}) signals assigned in this membership', ['usedsignals'=>$usedSignals, 'maxtext'=>$maxText]) ?></p>
	<?php endif; ?>

	<div class="botsignal-overview">
	<?php if (!empty($botsignalModel)) : ?>
		<table border="1" class="table-100 mt-2">
			<tr><th>#</th> <th><?= Yii::t('app', 'Action') ?></th> <th><?= Yii::t('app', 'Active') ?></th> <th><?= Yii::t('app', 'Name') ?></th></tr>

		<?php $bsgnr=0; foreach($botsignalModel as $bsgid => $botsignal) : $bsgnr++; ?>
			<tr><td><?= $bsgnr ?></td>
				<td>
					<?= HTML::a(/*Yii::t('app', 'Update botsignal')*/'Update', ['/user/updatebotsignal', 'id' => $botsignal->id]) ?>
					<?= Html::a('Delete', ['/user/delbotsignal', 'id'=>$botsignal->id], ['onclick'=>'return confirm("'.Yii::t('app','Are you sure you want to remove this signal?').'")']) ?>
				</td>
				<td><?= $yesNos[ $botsignal->bsg_active ] ?></td>
				<td><?= $botsignal->bsgsig->sig_name ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php	else : ?>
		<p class="mt-2"><?= Yii::t('app', 'No signal assigned') ?></p>
	<?php	endif; ?>
	</div>
</div>
<?php endif; ?>

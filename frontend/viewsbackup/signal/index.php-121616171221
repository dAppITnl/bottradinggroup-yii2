<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;
use richardfan\widget\JSRegister;

//use yii\helpers\Url; //manager;
//use \kartik\grid\GridView;
//use \frontend\models\User;

$memberships = GeneralHelper::getMembershipsForLanguage(Yii::$app->user->identity->usr_language);
$categorySignals = []; //GeneralHelper::getCategorySignalsForUserBotsignal(Yii::$app->user->identity->usr_language);

?>
<div class="signal-index">
  <div class="body-content">
		<div class="container">

			<div class="row">
				<div class="col text-center">
					<h1><?= Yii::t('app', 'Signals') ?></h1>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col col-sm-6">
					<div class="form-group ">
						<label class="has-star" for"selectMembership"><?= Yii::t('app', 'Choose membership') ?></label>
						<?= Html::dropDownList(null, null, $memberships, ['id'=>'selectMembership', 'class'=>'form-control']); ?>
					</div>
				</div>
        <div class="col col-sm-6">
          <div class="form-group">
            <label class="has-star" for"selectMembership"><?= Yii::t('app', 'Choose signal to view') ?></label>
						<?= Html::dropDownList(null, null, $categorySignals, ['id'=>'selectSignal', 'class'=>'form-control']); ?>
					</div>
				</div>
			</div>

			<div class="row mt-4">
        <div class="col">
					<table class="table">
						<tr><th><?= Yii::t('app', 'Signal')          ?>:</th><td class="table-title"><span id="sigName"></span></td></tr>
						<tr><th><?= Yii::t('app', 'Max nr of  bots') ?>:</th><td><span id="sigMaxbots"></span></td></tr>
						<tr><th><?= Yii::t('app', 'Base currency')   ?>:</th><td><span id="sigBase"></span></td></tr>
						<tr><th><?= Yii::t('app', 'Quote currency')  ?>:</th><td><span id="sigQuote"></span></td></tr>
						<tr><th><?= Yii::t('app', 'Description')     ?>:</th><td><span id="signalDetail"></span></td></tr>
					</table>
				</div>
			</div>

		</div>
	</div>
</div>

<?php JSRegister::begin([
    'key' => 'signal-index',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
$('#selectMembership').on('click', function(event) {
	//event.preventDefault();
	const mbrid = this.value;
	console.log('mbrid='+mbrid);
	$.get('/signal/getmembershipsignals?id='+mbrid, (response) => {
		console.log('selectMembership response: ', response);
		const data = JSON.parse(response);
		var sel = $('#selectSignal');
		sel.empty();
		var option, optGroup;
		var optGroupCount=0;
		$.each(data, function(index, item) {
			if (item.value<0) {
				if (optGroupCount>0) {
					sel.append(optGroup);
					optGroupCount=0;
				}
				optGroup=$("<optgroup>").attr('label', item.text);
				optGroupCount++;
			} else {
				option = $("<option></option>").attr("value", item.value).text(item.text);
				if (optGroupCount>0) optGroup.append(option);
				else sel.append(option);
			}
    });
		if (optGroupCount>0) sel.append(optGroup);
		$('#selectSignal').click();
	});
});
$('#selectMembership').click();

$('#selectSignal').on('click', function(event) {
  //event.preventDefault();

	const sigid = this.value;
	console.log('sigid:'+sigid);
	$.get('/signal/getsignal?id='+sigid, (response) => {
		//console.log('selectSignal response:', response);
		const data = JSON.parse(response);
		$('#sigName').text(data.name);
		$('#sigMaxbots').text( (data.maxbots==0 || data.maxbots=='') ? '<?= Yii::t('app', 'unlimited') ?>' : data.maxbots );
		$('#sigBase').text( (data.base == '') ? '<?= Yii::t('app', 'any') ?>' : data.base );
		$('#sigQuote').text( (data.quote == '') ? '<?= Yii::t('app', 'any') ?>' : data.quote );
		$('#signalDetail').html(data.html);
	});
});
</script>
<?php JSRegister::end(); ?>



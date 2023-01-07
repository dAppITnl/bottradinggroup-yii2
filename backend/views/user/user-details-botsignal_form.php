<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use frontend\models\Signal;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

Yii::trace('** view user-details-botsignal_form botsignalModel->id='.$botsignalModel->id);
Yii::trace('** view user-details-botsignal_form userbotModel->ubt_name='.$userbotModel->ubt_name);



$actionIsUpdate = !empty($botsignalModel->id);
$botName = Html::encode($userbotModel->ubt_name);

$signals = GeneralHelper::getCategorySignalsForUserBotsignal(
	Yii::$app->user->identity->usr_language,
	$userbotModel->ubtumb->umbmbr_id,
	'',
	''); //Signal::getSignalsForUserBotsignal();
Yii::trace('** botsignal_form signals: '.print_r($signals, true));

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="usermember-botsignal">
  <div class="body-content">
		<div class="container">
    <div class="row">
      <div class="col col-mt-12 text-center">
        <h1><?= ($actionIsUpdate
          ? Yii::t('app', "Update your signal for a 3Commas bot") //  '{botName}'.", ['botName' => $botName])
          : Yii::t('app', "Add a signal to a 3Commas bot")) /*'{botName}'.", ['botName' => $botName]))*/ ?></h1>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col col-mt-12 text-left">
        <p><?= Yii::t('app', "Bot") ?>:</p>
      </div>
    </div>

		<div class="row">
      <div class="col">
			  <?= DetailView::widget([
    			'model' => $userbotModel,
					'options' => ['class' => 'table'],
			    'attributes' => [
						[
              'format' => 'raw',
              //'attribute' => 'username',
              'label' => Yii::t('app', 'User'),
              'value' => $userbotModel->ubtumb->umbusr->username,  //$getPricelistPeriods[ $usermemberModel->umbprl->prl_percode ],
            ],
      			[
			        'format' => 'raw',
      			  //'attribute' => 'mbr_name',
							'label' => Yii::t('app', 'Membership'),
      			  'value' => $userbotModel->ubtumb->umbmbr->mbr_title,  //$getPricelistPeriods[ $usermemberModel->umbprl->prl_percode ],
			      ],
						'ubt_name',
			      'ubt_3cbotid',
						//'ubt_3cdealstartjson:ntext',
						'ubt_remarks:ntext',
			    ],
			  ]); ?>
			</div>
    </div>

    <div class="row mt-4">
      <div class="col col-mt-12 text-left">
        <p><?= Yii::t('app', "Choose signal") ?>:</p>
      </div>
    </div>

		<div class="row">
			<div class="col">

				<div class="botsignal-form">

			    <?php $form = ActiveForm::begin([
  		  	  'id' => 'Botsignal',
    		  	'layout' => 'horizontal',
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
    		 		],
		    	]); ?>

    			<div class="">
   				  <?= $form->field($botsignalModel, 'bsgsig_id')->dropDownList($signals,
       				[
			          'prompt' => Yii::t('cruds', 'Select'),
   				      'disabled' => (isset($relAttributes) && isset($relAttributes['bsgsig_id'])),
								'onChange' => 'getSignalDescription(this.value)',
      		 			]
	      		); ?>

						<?= $form->field($botsignalModel, 'bsg_active')->checkbox([ ]) ?>

						<div class="form-group row">
							<label class="col-sm-2"><?= Yii::t('app', 'Description') ?></label>
							<div class="col-sm-9 border border-info mx-3">
								<div id="sig_description" class="sigdescription"></div>
							</div>
						</div>

		      	<hr/>

    		  	<?php echo $form->errorSummary($botsignalModel); ?>

	      		<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . ($actionIsUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Add')),
		  	      ['id' => 'save-' . $botsignalModel->formName(),  'class' => 'btn btn-success']
    			  ); ?>

		      	<?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->getReferrer(), ['class' => 'btn btn-primary']) ?>
					</div>
     			<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
  </div>
</div>

<?php JSRegister::begin([
    'position' => \yii\web\View::POS_BEGIN
]); ?>
<script>
function getSignalDescription(id) {
	submit=false;
  try {
    //console.log('id='+id);
    if (id > 0) {
      $.get(
        '/botsignal/getsignaldescription?id='+id,
        (response) => {
          var data=JSON.parse(response);
          //console.log('data=',data);
          if (data.description.length > 0) {
            $('#sig_description').html(data.description);
          } else {
            $('#sig_description').html("<?= Yii::t('app', 'Error getting description.') ?> " + data.description);
          }
        }
      );
    } else {
      $('#sig_description').html("<?= Yii::t('app', 'Select a signal') ?>");
    }
  } catch (error) {
    console.error('submit error: '+error.code+'='+error.message);
  }
}

document.addEventListener("DOMContentLoaded", function(event) {
	var id = document.getElementById('botsignal-bsgsig_id').value;
	//console.log( "ready! id="+id );
	if (id > 0) getSignalDescription(id);
});
</script>
<?php JSRegister::end(); ?>


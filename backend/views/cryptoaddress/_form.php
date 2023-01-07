<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use backend\models\Pricelist;
use backend\models\Symbol;
use backend\models\User;
use backend\models\Membership;
use backend\models\Cryptoaddress;
use common\helpers\GeneralHelper;
use richardfan\widget\JSRegister;

$fiatSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_FIAT );
$cryptoSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_CRYPTO );

$userOwners = User::getUsersWithinLevelsUp(User::USR_SITELEVEL_ADMIN, User::USR_SITELEVEL_DEV);
$memberships = Membership::getMemberships('nl-NL');
$networks = Cryptoaddress::getNetworks();
$networkNames = Cryptoaddress::getNetworkNames();

/**
* @var yii\web\View $this
* @var backend\models\Cryptoaddress $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="cryptoaddress-form">
  <?php $form = ActiveForm::begin([
    'id' => 'Cryptoaddress',
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
    <?php $this->beginBlock('main'); ?>
    <p>
      <!-- attribute cad_active -->
      <?= $form->field($model, 'cad_active')->checkbox(/*['label'=>'']*/); ?>

      <!-- attribute cad_code -->
      <?= $form->field($model, 'cad_code')->textInput(['maxlength' => true]) ?>

      <!-- attribute cad_name -->
      <?= $form->field($model, 'cad_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute cad_description -->
      <?= $form->field($model, 'cad_description')->textarea(['rows' => 3]) ?>

			<hr>

			<!-- attribute cadusr_owner_id -->
			<?= $form->field($model, 'cadusr_owner_id')->dropDownList($userOwners,
    		[
       		'prompt' => Yii::t('cruds', 'Select'),
       		'disabled' => (isset($relAttributes) && isset($relAttributes['cadusr_owner_id'])),
    		]
			); ?>

      <!-- attribute cadmbr_ids -->
      <?= $form->field($model, 'cadmbr_ids')->checkboxList($memberships, ['separator' => '<!-- br -->']); ?>

			<!-- attribute cad_payprovider -->
			<?= $form->field($model, 'cad_payprovider')->dropDownList( GeneralHelper::getPayproviders(),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['cad_payprovider'])),
        ]
      ); ?>

			<hr>

      <!-- attribute cadsym_id -->
      <?= $form->field($model, 'cadsym_id')->dropDownList($cryptoSymbols,
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['cadsym_id'])),
        ]
      ); ?>

      <!-- attribute cad_address -->
      <?= $form->field($model, 'cad_address')->textInput(['maxlength' => true]) ?>

      <!-- attribute cad_memo -->
      <?= $form->field($model, 'cad_memo')->textInput(['maxlength' => true]) ?>

      <!-- attribute cad_decimals -->
      <?= $form->field($model, 'cad_decimals')->textInput(['type' => 'number', 'step' => '1']) ?>

      <!-- attribute cad_contract -->
      <?= $form->field($model, 'cad_contract')->textInput(['maxlength' => true]) ?>


			<!-- attribute cad_type -->
			<?= $form->field($model, 'cad_type')->dropDownList( backend\models\Cryptoaddress::optscadtype(),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['cad_type'])),
        ]
			); ?>

      <!-- attribute cad_ismainnet -->
      <?= $form->field($model, 'cad_ismainnet')->checkbox(/*['label'=>'']*/); ?>

			<!-- attribute cad_networkname -->
			<?= $form->field($model, 'cad_networkname')->dropDownList($networkNames,
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['cad_networkname'])),
        ]
      ); ?>
			<!-- attribute cad_networksettings -->
      <?= $form->field($model, 'cad_networksettings')->textInput(['maxlength' => true]) ?>

			<!-- attribute cad_tokenmetadata -->
			<?= $form->field($model, 'cad_tokenmetadata')->textarea(['rows' => 4]) ?>

			<!-- button id="updateNWSettings" type="button">Update Network settings</button --><button id="updateTokenmetadata" type="button">Update Tokenmetadata</button>

			<hr>

      <!-- attribute cad_remarks -->
      <?= $form->field($model, 'cad_remarks')->textarea(['rows' => 6]) ?>

      <!-- attribute cad_lock -->
      <?= '' // $form->field($model, 'cad_lock')->textInput() ?>

      <!-- attribute cad_createdt -->
      <?= '' // $form->field($model, 'cad_createdt')->textInput() ?>
      <!-- attribute cad_createdat -->
      <?= '' // $form->field($model, 'cad_createdat')->textInput() ?>
			<!-- attribute cadusr_created_id -->
			<?= '' /* $form->field($model, 'cadusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['cadusr_created_id'])),
    		]
			); */ ?>

      <!-- attribute cad_updatedt -->
      <?= '' //$form->field($model, 'cad_updatedt')->textInput() ?>
      <!-- attribute cad_updatedat -->
      <?= '' //$form->field($model, 'cad_updatedat')->textInput() ?>
			<!-- attribute cadusr_updated_id -->
			<?= '' /* $form->field($model, 'cadusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['cadusr_updated_id'])),
    		]
			); */ ?>

      <!-- attribute cad_deletedt -->
      <?= '' //$form->field($model, 'cad_deletedt')->textInput() ?>
      <!-- attribute cad_deletedat -->
      <?= '' // $form->field($model, 'cad_deletedat')->textInput() ?>
      <!-- attribute cadusr_deleted_id -->
      <?= '' /* $form->field($model, 'cadusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['cadusr_deleted_id'])),
        ]
      ); */ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget([
      'encodeLabels' => false,
      'items' => [
        [
          'label'   => Yii::t('models', 'Cryptoaddress'),
    			'content' => $this->blocks['main'],
    			'active'  => true,
				],
      ]
    ]); ?>

    <hr/>

    <?php echo $form->errorSummary($model); ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')),
      [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
      ]
    ); ?>

    <?php ActiveForm::end(); ?>

  </div>
</div>

<?php JSRegister::begin([
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
function updateTokenmetadata() {
	submit=false;
	try {
		var network = $("#cryptoaddress-cad_networkname").val();
		var symid = $("#cryptoaddress-cadsym_id option:selected").val();
		var symaddress = $('#cryptoaddress-cad_contract').val();
		console.log('updateTokenmetadata network='+network+' symid='+symid+' address='+symaddress);
		if (network!='' && symaddress!='') {
			$.post(
				'/cryptoaddress/getmoralistokenmetadata',
				{'network':network, 'symaddress':symaddress},
				(response) => {
					var data=JSON.parse(response);
					if (data.metadata) {
						console.log('response data: ',data);
						$("#cryptoaddress-cad_tokenmetadata").val(JSON.stringify(data.metadata));
					}
				}
			);
		}
	} catch (error) {
		console.error('updateTokenmetadata error: '+error.code+'='+error.message);
	}
};

//$('#updateNWSettings').on('click', updateNWSettings);
$('#updateTokenmetadata').on('click', updateTokenmetadata);

</script>
<?php JSRegister::end(); ?>


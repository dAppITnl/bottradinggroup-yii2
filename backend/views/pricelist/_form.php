<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use kartik\widgets\DatePicker; // http://demos.krajee.com/widget-details/datetimepicker#usage
use backend\models\Cryptoaddress;
use backend\models\Pricelist;
use backend\models\Symbol;
use \common\helpers\GeneralHelper;

//$yesNos = GeneralHelper::getYesNos(false);
$pricelistCategories = GeneralHelper::getCategoriesOfType('prl', true);
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$membershipsForLanguage = GeneralHelper::getMembershipsForLanguage(/*'nl-NL'*/'');

$fiatSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_FIAT );
//$cryptoSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_CRYPTO );
//$cryptoAddresses = Cryptoaddress::getCryptoAddresses();
$cryptoaddressesSymbols = Cryptoaddress::getCryptoaddressesSymbols(true, false);

/**
* @var yii\web\View $this
* @var backend\models\Pricelist $model
* @var yii\widgets\ActiveForm $form
*/

// **>> Fields umb_paystartdate , umb_payenddate only for payment cycle (temp dates for userpay record)

?>

<div class="pricelist-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Pricelist',
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
      <!-- attribute prl_name -->
      <?= $form->field($model, 'prl_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute prl_active -->
      <?= $form->field($model, 'prl_active')->checkBox() ?>

			<!-- attribute prl_active4admin -->
			<?= $form->field($model, 'prl_active4admin')->checkBox() ?>

			<!-- attribute prl_pretext -->
			<?= $form->field($model, 'prl_pretext')->textInput(['maxlength' => true]) ?>
			<!-- attribute prl_posttext -->
			<?= $form->field($model, 'prl_posttext')->textInput(['maxlength' => true]) ?>

			<!-- attribute prlmbr_id -->
			<?= $form->field($model, 'prlmbr_id')->dropDownList($membershipsForLanguage,
    		//\yii\helpers\ArrayHelper::map(backend\models\Membership::find()->all(), 'id', 'mbr_title'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlmbr_id'])),
    		]
			); ?>

			<!-- attribute prlcat_id -->
			<?= $form->field($model, 'prlcat_id')->dropDownList($pricelistCategories,
    		// \yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'cat_title'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlcat_id'])),
    		]
			); ?>

			<!-- attribute prl_startdate -->
      <?= $form->field($model, 'prl_startdate')->widget(DatePicker::classname(),
        [
          'options' => [
            'placeholder' => Yii::t('app', 'Enter start date...'),
            'class' => 'dtpinput'
            //'convertFormat' => true,
          ],
          'pluginOptions' => [
            'autoclose' => true,
            'calendarWeeks' => true,
            'todayBtn' => true,
            //'minuteStep' => 10, // assets/js/bootstrap-datetimepicker.js
            'format' => 'yyyy-mm-dd', //'dd-M-yyyy',
          ],
        ]
      ) ?>

			<!-- attribute prl_enddate -->
      <?= $form->field($model, 'prl_enddate')->widget(DatePicker::classname(),
        [
          'options' => [
            'placeholder' => Yii::t('app', 'Enter end date...'),
            'class' => 'dtpinput'
            //'convertFormat' => true,
          ],
          'pluginOptions' => [
            'autoclose' => true,
            'calendarWeeks' => true,
            'todayBtn' => true,
            //'minuteStep' => 10, // assets/js/bootstrap-datetimepicker.js
            'format' => 'yyyy-mm-dd', //'dd-M-yyyy',
          ],
        ]
      ) ?>

			<!-- attribute prlsym_id -->
			<?= $form->field($model, 'prlsym_id')->dropDownList($fiatSymbols,
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlsym_id'])),
    		]
			); ?>

			<!-- attribute prl_price -->
			<?= $form->field($model, 'prl_price')->textInput(['type' => 'number', 'step' => 'any']) ?>

      <!-- attribute prl_discountcode -->
      <?= $form->field($model, 'prl_discountcode')->textInput(['maxlength' => true]) ?>

      <!-- attribute prl_percode -->
      <?= $form->field($model, 'prl_percode')->dropDownList(
				$pricelistPeriods, //backend\models\Pricelist::optsprlpercode()
        [
          'prompt' => Yii::t('cruds', 'Select'),
        ]
			); ?>

			<!-- attribute prlcad_crypto_ids -->
			<?= $form->field($model, 'prlcad_crypto_ids')->checkboxList($cryptoaddressesSymbols, ['separator' => '<!-- br -->']); ?>


			<!-- attribute prl_allowedtimes -->
			<?= $form->field($model, 'prl_allowedtimes')->textInput(['type' => 'number', 'step' => '1']) ?>

			<!-- attribute prl_maxsignals -->
			<?= $form->field($model, 'prl_maxsignals')->textInput(['type' => 'number', 'step' => '1']) ?>

			<!-- attribute prl_remarks -->
      <?= $form->field($model, 'prl_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute prl_lock -->
      <?= '' //$form->field($model, 'prl_lock')->textInput() ?>

			<!-- attribute prl_createdt -->
			<?= '' //$form->field($model, 'prl_createdt')->textInput() ?>
      <!-- attribute prl_createdat -->
      <?= '' //$form->field($model, 'prl_createdat')->textInput() ?>
			<!-- attribute prlusr_created_id -->
			<?= '' /*$form->field($model, 'prlusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlusr_created_id'])),
    		]
			);*/ ?>

			<!-- attribute prl_updatedt -->
			<?= '' //$form->field($model, 'prl_updatedt')->textInput() ?>
      <!-- attribute prl_updatedat -->
      <?= '' //$form->field($model, 'prl_updatedat')->textInput() ?>
			<!-- attribute prlusr_updated_id -->
			<?= '' /*$form->field($model, 'prlusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlusr_updated_id'])),
    		]
			);*/ ?>

			<!-- attribute prl_deletedt -->
			<?= '' //$form->field($model, 'prl_deletedt')->textInput() ?>
      <!-- attribute prl_deletedat -->
      <?= '' //$form->field($model, 'prl_deletedat')->textInput() ?>
			<!-- attribute prlusr_deleted_id -->
			<?= '' /*$form->field($model, 'prlusr_deleted_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['prlusr_deleted_id'])),
    		]
			);*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget([
      'encodeLabels' => false,
      'items' => [
        [
    			'label'   => Yii::t('models', 'Pricelist'),
    			'content' => $this->blocks['main'],
    			'active'  => true,
				],
      ]
    ]); ?>

    <hr/>

    <?= $form->errorSummary($model); ?>

    <?= Html::submitButton(
      '<span class="glyphicon glyphicon-check"></span> ' .
        ($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')),
      [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
      ]
    ); ?>

    <?php ActiveForm::end(); ?>
  </div>
</div>


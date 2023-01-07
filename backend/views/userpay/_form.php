<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use common\helpers\GeneralHelper;
//use kartik\datetime\DateTimePicker;
use kartik\widgets\DatePicker; // http://demos.krajee.com/widget-details/datetimepicker#usage


$usermembers = [];
$payCategories = GeneralHelper::getCategoriesOfType('pay', true);
$userpayStates = GeneralHelper::getUserpayStates();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$userpayProvidertype = GeneralHelper::getUserpayProvidertype();
$payProviders = GeneralHelper::getPayproviders();

/**
* @var yii\web\View $this
* @var backend\models\Userpay $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="userpay-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Userpay',
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
      <!-- attribute upy_name -->
      <?= $form->field($model, 'upy_name')->textInput(['maxlength' => true]) ?>

			<!-- attribute upyusr_id -->
			<?= $form->field($model, 'upyusr_id')->dropDownList(
		   \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
		   [
		     'prompt' => Yii::t('cruds', 'Select'),
		     'disabled' => (isset($relAttributes) && isset($relAttributes['upyusr_id'])),
		   ]
			); ?>
			<!-- attribute upyumb_id -->
		  <?= $form->field($model, 'upyumb_id')->dropDownList( //$usermembers,
		    \yii\helpers\ArrayHelper::map(backend\models\Usermember::find()->where(['umb_deletedat'=>0])->all(), 'id', 'umb_name'),
		    [
		      'prompt' => Yii::t('cruds', 'Select'),
		      'disabled' => (isset($relAttributes) && isset($relAttributes['upyumb_id'])),
		    ]
		  ); ?>

      <!-- attribute upy_state -->
      <?= $form->field($model, 'upy_state')->dropDownList($userpayStates,
        // \yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          //'disabled' => (isset($relAttributes) && isset($relAttributes['upy_state'])),
        ]
      ); ?>

			<!-- attribute upycat_id -->
			<?= $form->field($model, 'upycat_id')->dropDownList(
    		$payCategories,  //\yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
      		'disabled' => (isset($relAttributes) && isset($relAttributes['upycate_id'])),
    		]
			); ?>

      <!-- attribute upy_paiddt -->
      <?= $form->field($model, 'upy_paiddt')->widget(DatePicker::classname(),
        [
          'options' => [
            'placeholder' => Yii::t('app', 'Enter payment date...'),
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

      <!-- attribute prl_percode -->
      <?= $form->field($model, 'upy_percode')->dropDownList(
        $pricelistPeriods, //backend\models\Pricelist::optsprlpercode()
        [
          'prompt' => Yii::t('cruds', 'Select'),
        ]
      ); ?>

			<!-- attribute upy_startdate -->
			<?= $form->field($model, 'upy_startdate')->widget(DatePicker::classname(),
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

      <!-- attribute upy_enddate -->
      <?= $form->field($model, 'upy_enddate')->widget(DatePicker::classname(),
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

      <!-- attribute upyprl_id -->
      <?= $form->field($model, 'upyprl_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Pricelist::find()->all(), 'id', 'prl_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upyprl_id'])),
        ]
      ); ?>

      <!-- attribute upysym_pay_id -->
      <?= $form->field($model, 'upysym_pay_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->all(), 'id', 'sym_code'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upysym_pay_id'])),
        ]
      ); ?>

			<!-- attribute upy_payamount -->
			<?= $form->field($model, 'upy_payamount')->textInput(['type' => 'number', 'step' => 'any']) ?>

			<!-- attribute upy_discountcode -->
			<?= $form->field($model, 'upy_discountcode')->textInput(['maxlength' => true]) ?>

      <!-- attribute upy_fromaccount -->
      <?= $form->field($model, 'upy_fromaccount')->textInput(['maxlength' => true]) ?>

			<!-- attribute upysym_crypto_id -->
		  <?= $form->field($model, 'upysym_crypto_id')->dropDownList(
		   \yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->all(), 'id', 'id'),
		   [
		       'prompt' => Yii::t('cruds', 'Select'),
		       'disabled' => (isset($relAttributes) && isset($relAttributes['upysym_crypto_id'])),
		   ]
			); ?>

			<!-- attribute upy_cryptoamount -->
      <?= $form->field($model, 'upy_cryptoamount')->textInput(['type' => 'number', 'step' => 'any']) ?>

			<!-- attribute upycad_to_id -->
		  <?= $form->field($model, 'upycad_to_id')->dropDownList(
		   	\yii\helpers\ArrayHelper::map(backend\models\Cryptoaddress::find()->all(), 'id', 'cad_name'),
		   [
		     'prompt' => Yii::t('cruds', 'Select'),
		     'disabled' => (isset($relAttributes) && isset($relAttributes['upycad_to_id'])),
		   ]
			); ?>

      <!-- attribute upy_toaccount -->
      <?= $form->field($model, 'upy_toaccount')->textInput(['maxlength' => true]) ?>

      <!-- attribute upysym_rate_id -->
      <?= $form->field($model, 'upysym_rate_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->all(), 'id', 'sym_code'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upysym_rate_id'])),
        ]
      ); ?>

      <!-- attribute upy_rate -->
      <?= $form->field($model, 'upy_rate')->textInput(['type' => 'number', 'step' => 'any']) ?>

			<!-- attribute upy_payprovider -->
		  <?= $form->field($model, 'upy_payprovider')->dropDownList($payProviders,
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upy_payprovider'])),
        ]
		  ); ?>

		 	<!-- attribute upy_providerid -->
	    <?= '' // $form->field($model, 'upy_providerid')->textInput(['maxlength' => true]) ?>

			<!-- attribute upy_providername -->
		  <?= $form->field($model, 'upy_providername')->textInput(['maxlength' => true]) ?>

			<!-- attribute upy_payref -->
		  <?= $form->field($model, 'upy_payref')->textarea(['rows' => 2]) ?>

			<!-- attribute upy_payreply -->
		  <?= $form->field($model, 'upy_payreply')->textarea(['rows' => 2]) ?>

			<!-- attribute upy_maxsignals -->
			<?= $form->field($model, 'upy_maxsignals')->textInput(['type' => 'number', 'step' => '1']) ?>

      <!-- attribute upy_remarks -->
      <?= $form->field($model, 'upy_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute upy_lock -->
      <?= '' //$form->field($model, 'upy_lock')->textInput() ?>

      <!-- attribute upy_createdat -->
      <?= '' // $form->field($model, 'upy_createdat')->textInput() ?>
			<!-- attribute upy_createdt -->
			<?= '' // $form->field($model, 'upy_createdt')->textInput() ?>
      <!-- attribute upyusr_created_id -->
      <?= '' /* $form->field($model, 'upyusr_created_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upyusr_created_id'])),
        ]
      );*/ ?>

      <!-- attribute upy_updatedat -->
      <?= '' // $form->field($model, 'upy_updatedat')->textInput() ?>
			<!-- attribute upy_updatedt -->
			<?= '' // $form->field($model, 'upy_updatedt')->textInput() ?>
      <!-- attribute upyusr_updated_id -->
      <?= '' /* $form->field($model, 'upyusr_updated_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['upyusr_updated_id'])),
        ]
      );*/ ?>

			<!-- attribute upy_deletedat -->
			<?= '' // $form->field($model, 'upy_deletedat')->textInput() ?>
      <!-- attribute upy_deletedt -->
      <?= '' // $form->field($model, 'upy_deletedt')->textInput() ?>
			<!-- attribute upyusr_deleted_id -->
			<?= '' /* $form->field($model, 'upyusr_deleted_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['upyusr_deleted_id'])),
    		]
			);*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
    				'label'   => Yii::t('models', 'Userpay'),
    				'content' => $this->blocks['main'],
    				'active'  => true,
					],
        ]
      ]
    ); ?>

    <hr/>

    <?php echo $form->errorSummary($model); ?>

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


y<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use kartik\widgets\DatePicker; // http://demos.krajee.com/widget-details/datetimepicker#usage
use \common\helpers\GeneralHelper;

$mbrCategories = GeneralHelper::getCategoriesOfType('mbr', false);
//$mbrRoles = GeneralHelper::getMembershipRoles();

/**
* @var yii\web\View $this
* @var backend\models\Usermember $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="usermember-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Usermember',
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
      <!-- attribute umb_name -->
      <?= $form->field($model, 'umb_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute umb_active -->
      <?= $form->field($model, 'umb_active')->checkbox() ?>

      <!-- attribute umb_roles -->
      <?= '' /* $form->field($model, 'umb_roles')->dropDownList($mbrRoles,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]); /*->label("Add Categories")*/ ?>

      <!-- attribute umb_startdate -->
      <?= '' /* $form->field($model, 'umb_startdate')->widget(DatePicker::classname(),
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
      )*/ ?>

      <!-- attribute umb_enddate -->
      <?= '' /* $form->field($model, 'umb_enddate')->widget(DatePicker::classname(),
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
      )*/ ?>

			<!-- attribute umbusr_id -->
			<?= $form->field($model, 'umbusr_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['umbusr_id'])),
    		]
			); ?>

			<!-- attribute umbmbr_id -->
			<?= $form->field($model, 'umbmbr_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\Membership::find()->all(), 'id', 'mbr_title'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['umbmbr_id'])),
    		]
			); ?>

			<!-- attribute umbprl_id -->
      <?= '' /* $form->field($model, 'umbprl_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Pricelist::find()->all(), 'id', 'prl_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['umbprl_id'])),
        ]
      );*/ ?>

			<!-- attribute umbupy_id -->
		  <?= '' /*$form->field($model, 'umbupy_id')->dropDownList(
		    \yii\helpers\ArrayHelper::map(backend\models\Userpay::find()->all(), 'id', 'id'),
		    [
		      'prompt' => Yii::t('cruds', 'Select'),
		      'disabled' => (isset($relAttributes) && isset($relAttributes['umbupy_id'])),
		    ]
			); */ ?>

			<!-- attribute umb_maxsignals -->
			<?= '' /*$form->field($model, 'umb_maxsignals')->textInput(['type' => 'number', 'min' => 0, 'step' => '1'])*/ ?>

      <!-- attribute umb_remarks -->
      <?= $form->field($model, 'umb_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute umb_lock -->
      <?= '' //$form->field($model, 'umb_lock')->textInput() ?>

			<!-- attribute umb_createdt -->
			<?= '' //$form->field($model, 'umb_createdt')->textInput() ?>
      <!-- attribute umb_createdat -->
      <?= '' //$form->field($model, 'umb_createdat')->textInput() ?>
      <!-- attribute umbusr_created_id -->
      <?= '' /*$form->field($model, 'umbusr_created_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['umbusr_created_id'])),
        ]
      );*/ ?>

      <!-- attribute umb_updatedt -->
      <?= '' //$form->field($model, 'umb_updatedt')->textInput() ?>
			<!-- attribute umb_updatedat -->
			<?= '' //$form->field($model, 'umb_updatedat')->textInput() ?>
      <!-- attribute umbusr_updated_id -->
      <?= '' /*$form->field($model, 'umbusr_updated_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['umbusr_updated_id'])),
        ]
      );*/ ?>

			<!-- attribute umb_deletedt -->
			<?= '' //$form->field($model, 'umb_deletedt')->textInput() ?>
      <!-- attribute umb_deletedat -->
      <?= '' //$form->field($model, 'umb_deletedat')->textInput() ?>
      <!-- attribute umbusr_deleted_id -->
      <?= '' /*$form->field($model, 'umbusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['umbusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget([
      'encodeLabels' => false,
      'items' => [
        [
    			'label'   => Yii::t('models', 'Usermember'),
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


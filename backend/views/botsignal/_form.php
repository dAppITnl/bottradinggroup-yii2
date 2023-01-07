<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;
use backend\models\Signal;
//use kartik\select2\Select2;
use kartik\widgets\DatePicker; // http://demos.krajee.com/widget-details/datetimepicker#usage
use kartik\checkbox\CheckboxX;

$catTypes = GeneralHelper::getCategoriesOfType('bot', false);

//$signals = Signal::getSignalsForUserBotsignal();

/**
* @var yii\web\View $this
* @var backend\models\Botsignal $model
* @var yii\widgets\ActiveForm $form
*/

?>

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

    <?php $this->beginBlock('main'); ?>
    <p>
      <!-- attribute bsg_name -->
      <?= '' //$form->field($model, 'bsg_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute bsg_active -->
      <?= $form->field($model, 'bsg_active')->checkbox(/*['label'=>'']*/); /*widget([
          'model' => $model,
          'attribute' => 'status',
          'pluginOptions' => [
            'threeState' => false,
            'size' => 'lg'
          ]
        ]);*/ ?>

			<!-- attribute bsgubt_id -->
			<?= $form->field($model, 'bsgubt_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\Userbot::find()->all(), 'id', 'ubt_name'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['bsgubt_id'])),
    		]
			); ?>

      <!-- attribute bsgsig_ids -->
      <?= '' /*$form->field($model, 'bsgsig_ids')->dropDownList($signals,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]);*/ /*->label("Add Categories");*/ ?>

			<!-- attribute bsgsig_id -->
			<?= $form->field($model, 'bsgsig_id')->dropDownList(
				\yii\helpers\ArrayHelper::map(backend\models\Signal::find()->all(), 'id', 'sig_name'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['bsgsig_id'])),
    		]
			); ?>

			<!-- attribute bsg_remarks -->
			<?= $form->field($model, 'bsg_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute bsg_lock -->
      <?= '' // $form->field($model, 'bsg_lock')->textInput() ?>

      <!-- attribute bsg_createdat -->
      <?= '' // $form->field($model, 'bsg_createdat')->textInput() ?>
      <!-- attribute bsg_createdt -->
      <?= '' // $form->field($model, 'bsg_createdt')->textInput() ?>
			<!-- attribute bsgusr_created_id -->
			<?= '' /* $form->field($model, 'bsgusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['bsgusr_created_id'])),
    		]
			);*/ ?>

      <!-- attribute bsg_updatedat -->
      <?= '' //$form->field($model, 'bsg_updatedat')->textInput() ?>
      <!-- attribute bsg_updatedt -->
      <?= '' // $form->field($model, 'bsg_updatedt')->textInput() ?>
			<!-- attribute bsgusr_updated_id -->
			<?= '' /* $form->field($model, 'bsgusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['bsgusr_updated_id'])),
    		]
			);*/ ?>

      <!-- attribute bsg_deletedat -->
      <?= '' // $form->field($model, 'bsg_deletedat')->textInput() ?>
      <!-- attribute bsg_deletedt -->
      <?= '' // $form->field($model, 'bsg_deletedt')->textInput() ?>
      <!-- attribute bsgusr_deleted_id -->
      <?= '' /* $form->field($model, 'bsgusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['bsgusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

  <?= Tabs::widget(
    [
      'encodeLabels' => false,
      'items' => [
        [
    			'label'   => Yii::t('models', 'Botsignal'),
    			'content' => $this->blocks['main'],
    			'active'  => true,
				],
      ]
    ]
  ); ?>

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

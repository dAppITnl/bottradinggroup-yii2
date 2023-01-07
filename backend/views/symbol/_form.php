<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use backend\models\Symbol;
use yii\helpers\StringHelper;
use common\helpers\GeneralHelper;

$symbolTypes = GeneralHelper::getSymbolTypes();

/**
* @var yii\web\View $this
* @var backend\models\Symbol $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="symbol-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Symbol',
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
      <!-- attribute sym_type -->
      <?= $form->field($model, 'sym_type')->dropDownList( $symbolTypes /*backend\models\Symbol::optssymtype()*/ ); ?>

      <!-- attribute sym_ispair -->
      <?= $form->field($model, 'sym_ispair')->checkBox() ?>

      <!-- attribute sym_isquote -->
      <?= $form->field($model, 'sym_isquote')->checkBox() ?>

      <!-- attribute sym_name -->
      <?= $form->field($model, 'sym_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute sym_code -->
      <?= $form->field($model, 'sym_code')->textInput(['maxlength' => true]) ?>

      <!-- attribute sym_symbol -->
      <?= $form->field($model, 'sym_symbol')->textInput(['maxlength' => true]) ?>

			<!-- attribute sym_html -->
			<?= $form->field($model, 'sym_html')->textInput(['maxlength' => true]) ?>

			<!-- attribute symsym_base_id -->
			<?= $form->field($model, 'symsym_base_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->where(['sym_ispair'=>0, 'sym_deletedat'=>0])->all(), 'id', 'sym_code'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['symsym_base_id'])),
    		]
			); ?>

			<!-- attribute symsym_quote_id -->
			<?= $form->field($model, 'symsym_quote_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->where(['sym_ispair'=>0, 'sym_deletedat'=>0])->all(), 'id', 'sym_code'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['symsym_quote_id'])),
    		]
			); ?>

			<!-- attribute symsym_network_id -->
			<?= $form->field($model, 'symsym_network_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->where(['sym_ispair'=>0, 'sym_deletedat'=>0])->all(), 'id', 'sym_code'),
    		[
       		'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['symsym_network_id'])),
    		]
			); ?>

      <!-- attribute sym_contractaddress -->
      <?= $form->field($model, 'sym_contractaddress')->textInput(['maxlength' => true]) ?>

      <!-- attribute sym_rateurl -->
      <?= $form->field($model, 'sym_rateurl')->textarea(['rows' => 6]) ?>

			<!-- attribute sym_description -->
			<?= $form->field($model, 'sym_description')->textarea(['rows' => 6]) ?>

      <!-- attribute sym_remarks -->
      <?= $form->field($model, 'sym_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute sym_lock -->
      <?= '' //$form->field($model, 'sym_lock')->textInput() ?>

      <!-- attribute sym_createdat -->
      <?= '' //$form->field($model, 'sym_createdat')->textInput() ?>
      <!-- attribute sym_createdt -->
      <?= '' // $form->field($model, 'sym_createdt')->textInput() ?>
      <!-- attribute symusr_created_id -->
      <?= '' /*$form->field($model, 'symusr_created_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['symusr_created_id'])),
        ]
      );*/ ?>

      <!-- attribute sym_updatedat -->
      <?= '' //$form->field($model, 'sym_updatedat')->textInput() ?>
      <!-- attribute sym_updatedt -->
      <?= '' //$form->field($model, 'sym_updatedt')->textInput() ?>
      <!-- attribute symusr_updated_id -->
      <?= '' /* $form->field($model, 'symusr_updated_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['symusr_updated_id'])),
        ]
      );*/ ?>

      <!-- attribute sym_deletedat -->
      <?= '' // $form->field($model, 'sym_deletedat')->textInput() ?>
      <!-- attribute sym_deletedt -->
      <?= '' //$form->field($model, 'sym_deletedt')->textInput() ?>
      <!-- attribute symusr_deleted_id -->
      <?= '' /* $form->field($model, 'symusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['symusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget([
      'encodeLabels' => false,
      'items' => [
        [
    			'label'   => Yii::t('models', 'Symbol'),
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


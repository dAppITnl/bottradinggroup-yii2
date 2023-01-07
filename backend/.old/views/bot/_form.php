<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;
use kartik\select2\Select2;

/**
* @var yii\web\View $this
* @var backend\models\Bot $model
* @var yii\widgets\ActiveForm $form
*/

$catTypes = GeneralHelper::getCategoriesOfType('bot', false);

?>

<div class="bot-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Bot',
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
      <!-- attribute bot_name -->
      <?= $form->field($model, 'bot_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute botcat_id -->
			<?= $form->field($model, 'botcat_id')->/*widget(Select2::classname(), [
        'data' => $catTypes,
        'options' => ['prompt' => 'Select a category ...'],
        'pluginOptions' => [
          'allowClear' => true
        ],
      ]);*/dropDownList($catTypes,
    		//\yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select...'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['botcat_id'])),
    		]
			); ?>

			<!-- attribute bot_3cbotid -->
			<?= $form->field($model, 'bot_3cbotid')->textInput(['maxlength' => true]) ?>

			<!-- attribute bot_dealstartjson -->
			<?= $form->field($model, 'bot_dealstartjson')->textarea(['rows' => 6]) ?>

      <!-- attribute botsym_cost_id -->
      <?= $form->field($model, 'botsym_cost_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Symbol::find()->all(), 'id', 'sym_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['botsym_cost_id'])),
        ]
      ); ?>

      <!-- attribute bot_costmonth -->
      <?= $form->field($model, 'bot_costmonth')->textInput() ?>

      <!-- attribute bot_remarks -->
      <?= $form->field($model, 'bot_remarks')->textarea(['rows' => 6]) ?>

      <!-- attribute bot_lock -->
      <?= '' // $form->field($model, 'bot_lock')->textInput() ?>



      <!-- attribute bot_createdat -->
      <?= '' // $form->field($model, 'bot_createdat')->textInput() ?>

			<!-- attribute bot_createdt -->
			<?= '' // $form->field($model, 'bot_createdt')->textInput() ?>

      <!-- attribute botusr_created_id -->
      <?= '' /*$form->field($model, 'botusr_created_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['botusr_created_id'])),
        ]
      );*/ ?>

      <!-- attribute bot_updatedat -->
      <?= '' //$form->field($model, 'bot_updatedt')->textInput() ?>

      <!-- attribute bot_updatedt -->
      <?= '' //$form->field($model, 'bot_updatedt')->textInput() ?>

      <!-- attribute botusr_updated_id -->
      <?= '' /* $form->field($model, 'botusr_updated_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['botusr_updated_id'])),
        ]
      );*/ ?>

			<!-- attribute bot_deletedat -->
			<?= '' //$form->field($model, 'bot_deletedat')->textInput() ?>

      <!-- attribute bot_deletedt -->
      <?= '' //$form->field($model, 'bot_deletedt')->textInput() ?>

			<!-- attribute botusr_deleted_id -->
			<?= '' /*$form->field($model, 'botusr_deleted_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['botusr_deleted_id'])),
    		]
			);*/ ?>

		</p>
    <?php $this->endBlock(); ?>

  <?= Tabs::widget(
    [
      'encodeLabels' => false,
      'items' => [
        [
  				'label'   => Yii::t('models', 'Bot'),
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


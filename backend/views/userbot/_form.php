<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

/**
* @var yii\web\View $this
* @var backend\models\Userbot $model
* @var yii\widgets\ActiveForm $form
*/

$catTypes = GeneralHelper::getCategoriesOfType('bot', false);

?>

<div class="userbot-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Userbot',
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
      <!-- attribute ubt_name -->
      <?= $form->field($model, 'ubt_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute ubt_active -->
      <?= $form->field($model, 'ubt_active')->checkbox(/*['label'=>'']*/) ?>

			<!-- attribute ubt_signalstartstop -->
			<?= $form->field($model, 'ubt_signalstartstop')->checkbox(/*['label'=>'']*/) ?>

			<!-- attribute ubt_userstartstop -->
			<?= $form->field($model, 'ubt_userstartstop')->checkbox(/*['label'=>'']*/) ?>

      <!-- attribute ubtcat_id -->
      <?= $form->field($model, 'ubtcat_id')->dropDownList($catTypes,
        // \yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['ubtcat_id'])),
        ]
      ); ?>

			<!-- attribute ubtumb_id -->
		  <?= $form->field($model, 'ubtumb_id')->dropDownList(
		   	\yii\helpers\ArrayHelper::map(backend\models\Usermember::find()->all(), 'id', 'umb_name'),
		    [
		      'prompt' => Yii::t('cruds', 'Select'),
		      'disabled' => (isset($relAttributes) && isset($relAttributes['ubtumb_id'])),
		    ]
			); ?>

			<!-- attribute ubt_3cbotid -->
			<?= $form->field($model, 'ubt_3cbotid')->textInput(['maxlength' => true]) ?>

			<!-- attribute ubt_3cdealstartjson -->
			<?= $form->field($model, 'ubt_3cdealstartjson')->textarea(['rows' => 6]) ?>

			<!-- attribute ubt_remarks -->
			<?= $form->field($model, 'ubt_remarks')->textarea(['rows' => 6]) ?>

      <!-- attribute ubt_lock -->
      <?= '' //$form->field($model, 'ubt_lock')->textInput() ?>

      <!-- attribute ubt_createdat -->
      <?= '' //$form->field($model, 'ubt_createdat')->textInput() ?>
      <!-- attribute ubt_createdt -->
      <?= '' //$form->field($model, 'ubt_createdt')->textInput() ?>
			<!-- attribute ubtusr_created_id -->
			<?= '' /*$form->field($model, 'ubtusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['ubtusr_created_id'])),
    		]
			);*/ ?>

      <!-- attribute ubt_updatedat -->
      <?= '' //$form->field($model, 'ubt_updatedat')->textInput() ?>
      <!-- attribute ubt_updatedt -->
      <?= '' //$form->field($model, 'ubt_updatedt')->textInput() ?>
			<!-- attribute ubtusr_updated_id -->
			<?= '' /*$form->field($model, 'ubtusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['ubtusr_updated_id'])),
    		]
			);*/ ?>

      <!-- attribute ubt_deletedat -->
      <?= '' //$form->field($model, 'ubt_deletedat')->textInput() ?>
      <!-- attribute ubt_deletedt -->
      <?= '' //$form->field($model, 'ubt_deletedt')->textInput() ?>
      <!-- attribute ubtusr_deleted_id -->
      <?= '' /*$form->field($model, 'ubtusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['ubtusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
    				'label'   => Yii::t('models', 'Userbot'),
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

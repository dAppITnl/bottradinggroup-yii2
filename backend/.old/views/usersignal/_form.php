<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;

/**
* @var yii\web\View $this
* @var backend\models\Usersignal $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="usersignal-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Usersignal',
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
      <!-- attribute usg_name -->
      <?= $form->field($model, 'usg_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute usgusr_id -->
      <?= $form->field($model, 'usgusr_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'username'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['usgusr_id'])),
        ]
      ); ?>

      <!-- attribute usgbot_id -->
      <?= $form->field($model, 'usgbot_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Bot::find()->all(), 'id', 'bot_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['usgbot_id'])),
        ]
      ); ?>

      <!-- attribute usg_emailtoken -->
      <?= $form->field($model, 'usg_emailtoken')->textInput(['maxlength' => true]) ?>

      <!-- attribute usg_pair -->
      <?= $form->field($model, 'usg_pair')->textInput(['maxlength' => true]) ?>

      <!-- attribute usg_remarks -->
      <?= $form->field($model, 'usg_remarks')->textarea(['rows' => 6]) ?>

      <!-- attribute usg_lock -->
      <?= '' // $form->field($model, 'usg_lock')->textInput() ?>

			<!-- attribute usg_createdt -->
			<?= '' // $form->field($model, 'usg_createdt')->textInput() ?>

			<!-- attribute usg_updatedt -->
			<?= '' // $form->field($model, 'usg_updatedt')->textInput() ?>

			<!-- attribute usg_deletedat -->
			<?= '' // $form->field($model, 'usg_deletedat')->textInput() ?>

			<!-- attribute usgusr_deleted_id -->
			<?= '' /*$form->field($model, 'usgusr_deleted_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['usgusr_deleted_id'])),
    		]
			);*/ ?>

			<!-- attribute usg_deletedt -->
			<?= '' // $form->field($model, 'usg_deletedt')->textInput() ?>

			<!-- attribute usgusr_created_id -->
			<?= '' /*$form->field($model, 'usgusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['usgusr_created_id'])),
    		]
			);*/ ?>

			<!-- attribute usgusr_updated_id -->
			<?= '' /*$form->field($model, 'usgusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['usgusr_updated_id'])),
    		]
			);*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
						'label'   => Yii::t('models', 'Usersignal'),
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


<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
//use common\helpers\GeneralHelper;

//$getSignallogStates = GeneralHelper::getSignallogStates();

/**
* @var yii\web\View $this
* @var backend\models\Signallog $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="signallog-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Signallog',
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
      <!-- attribute slgbsg_id -->
      <?= '' /* less sinfull: rfeference already via message clear enough: [umb_name]->[ubt_name|ubt.id]: ..
				$form->field($model, 'slgbsg_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\Botsignal::find()->all(), 'id', 'bsg_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['slgbsg_id'])),
        ]
      );*/ ?>

			<!-- attribute slgsig_id -->
		  <?= $form->field($model, 'slgsig_id')->dropDownList(
		   \yii\helpers\ArrayHelper::map(backend\models\Signal::find()->all(), 'id', 'sig_name'),
		   [
		       'prompt' => Yii::t('cruds', 'Select'),
		       'disabled' => true || (isset($relAttributes) && isset($relAttributes['slgsig_id'])),
		   ]
			); ?>

      <!-- attribute slg_status -->
      <?= $form->field($model, 'slg_status')->textInput(['maxlength' => true, 'disabled' => true])
				/*$form->field($model, 'slg_status')->dropDownList($getSignallogStates,
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => true, // (isset($relAttributes) && isset($relAttributes['upy_state'])),
        ]
      );*/ ?>

			<!-- attribute slg_alertmsg -->
			<?= $form->field($model, 'slg_alertmsg')->textarea(['rows' => 3, 'disabled' => true]) ?>

      <!-- attribute slg_senddata -->
      <?= $form->field($model, 'slg_senddata')->textarea(['rows' => 7, 'disabled' => true]) ?>

      <!-- attribute slg_message -->
      <?= $form->field($model, 'slg_message')->textInput(['maxlength' => true, 'disabled' => true]) ?>

			<!-- attribute slg_discordlogmessage -->
			<?= $form->field($model, 'slg_discordlogmessage')->textInput(['maxlength' => true, 'disabled' => true]) ?>

			<!-- attribute slg_discordlogchanid -->
			<?= $form->field($model, 'slg_discordlogchanid')->textInput(['maxlength' => true, 'disabled' => true]) ?>

			<!-- attribute slg_discordtologat -->
			<?= $form->field($model, 'slg_discordtologat')->textInput(['disabled' => true]) ?>

			<!-- attribute slg_discordlogdone -->
			<?= $form->field($model, 'slg_discordlogdone')->textInput(['maxlength' => true, 'disabled' => true]) ?>

			<!-- attribute slg_discordlogdelaydone -->
			<?= $form->field($model, 'slg_discordlogdelaydone')->textInput(['maxlength' => true]) ?>

      <!-- attribute slg_remarks -->
      <?= $form->field($model, 'slg_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute slg_lock -->
      <?= '' //$form->field($model, 'slg_lock')->textInput() ?>

      <!-- attribute slg_createdt -->
      <?= '' //$form->field($model, 'slg_createdt')->textInput() ?>
      <!-- attribute slg_createdat -->
      <?= '' //$form->field($model, 'slg_createdat')->textInput() ?>
			<!-- attribute slgusr_created_id -->
			<?=	'' /*$form->field($model, 'slgusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['slgusr_created_id'])),
    		]
			);*/ ?>

      <!-- attribute slg_updatedt -->
      <?= '' //$form->field($model, 'slg_updatedt')->textInput() ?>
      <!-- attribute slg_updatedat -->
      <?= '' //$form->field($model, 'slg_updatedat')->textInput() ?>
			<!-- attribute slgusr_updated_id -->
			<?= '' /*$form->field($model, 'slgusr_updated_id')->dropDownList(
		    \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['slgusr_updated_id'])),
    		]
			);*/ ?>

      <!-- attribute slg_deletedt -->
      <?= '' //$form->field($model, 'slg_deletedt')->textInput() ?>
      <!-- attribute slg_deletedat -->
      <?= '' //$form->field($model, 'slg_deletedat')->textInput() ?>
      <!-- attribute slgusr_deleted_id -->
      <?= '' /*$form->field($model, 'slgusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['slgusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
    				'label'   => Yii::t('models', 'Signallog'),
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


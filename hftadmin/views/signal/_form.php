<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use hftadmin\models\Signal;
use hftadmin\models\Symbol;
//use hftadmin\models\Membership;
use \bizley\quill\Quill;
use common\helpers\GeneralHelper;

$sig3CActiontexts = Signal::optsSig3cActionTexts();

$getBaseSymbols = Symbol::getSymbols(false);
$getQuoteSymbols = Symbol::getSymbols(true);

$memberships = GeneralHelper::getMembershipsForLanguage('');
//$categories = GeneralHelper::getCategoriesOfType('sig', false, '');

/**
* @var yii\web\View $this
* @var hftadmin\models\Signal $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="signal-form">

  <?php $form = ActiveForm::begin([
	  'id' => 'Signal',
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
      <!-- attribute sig_name -->
      <?= $form->field($model, 'sig_name')->textInput(['maxlength' => true]) ?>

      <!-- attribute sig_code -->
      <?= $form->field($model, 'sig_code')->textInput(['maxlength' => true]) ?>

      <!-- attribute sig_active -->
      <?= $form->field($model, 'sig_active')->checkbox(/*['label'=>'']*/); ?>

			<!-- attribute sig_active4admin -->
			<?= $form->field($model, 'sig_active4admin')->checkbox(/*['label'=>'']*/); ?>

			<!-- attribute sig_runenabled -->
			<?= '' // $form->field($model, 'sig_runenabled')->checkbox(/*['label'=>'']*/); ?>

      <!-- attribute sig_maxbots -->
      <?= '' //$form->field($model, 'sig_maxbots')->textInput(['type' => 'number', 'min'=>0]) ?>

      <!-- attribute sigcat_ids -->
      <?= '' /*$form->field($model, 'sigcat_ids')->dropDownList($categories,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]) / * ->label("Add Categories");* /;*/ ?>

			<!-- attribute sigmbr_ids -->
      <?= $form->field($model, 'sigmbr_ids')->dropDownList($memberships,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]) /*->label("Add Categories")*/; ?>


      <!-- attribute sig_3cactiontext -->
      <?= //$form->field($model, 'sig_3cactiontext')->textInput(['maxlength' => true])
      	$form->field($model, 'sig_3cactiontext')->dropDownList($sig3CActiontexts,
        // \yii\helpers\ArrayHelper::map(hftadmin\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['sig_3cactiontext'])),
        ]
      ); ?>

      <!-- attribute sigsym_base_id -->
      <?= $form->field($model, 'sigsym_base_id')->dropDownList($getBaseSymbols,
        // \yii\helpers\ArrayHelper::map(hftadmin\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['sigsym_base_id'])),
        ]
      ); ?>

      <!-- attribute sigsym_quote_id -->
      <?= $form->field($model, 'sigsym_quote_id')->dropDownList($getQuoteSymbols,
        // \yii\helpers\ArrayHelper::map(hftadmin\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['sigsym_quote_id'])),
        ]
      ); ?>


      <!-- attribute sig_3callowedquotes -->
      <?= '' //$form->field($model, 'sig_3callowedquotes')->textarea(['rows' => 3]) ?>

			<!-- attribute sig_discordlogchanid -->
			<?= $form->field($model, 'sig_discordlogchanid')->textInput(['type' => 'number', 'min'=>0]) ?>

			<!-- attribute sig_discordlogdelaychanid -->
			<?= $form->field($model, 'sig_discordlogdelaychanid')->textInput(['type' => 'number', 'min'=>0]) ?>

			<!-- attribute sig_discordmessage -->
			<?= $form->field($model, 'sig_discordmessage')->textInput(['maxlength' => true]) ?>

			<!-- attribute sig_discorddelayminutes -->
			<?= $form->field($model, 'sig_discorddelayminutes')->textInput(['type' => 'number', 'min'=>0]) ?>

      <!-- attribute sig_description -->
      <?= $form->field($model, 'sig_description')/*->widget(Quill::classname(), [
				'toolbarOptions' => 'FULL', //[['header', 'bold', 'italic', 'underline', 'strike'], ['font'=>[], 'alignment'=>[], 'color'=>[], 'image'=>[]]],
			]) */->textarea(['rows' => 8]) ?>

			<!-- attribute sig_remarks -->
			<?= $form->field($model, 'sig_remarks')->textarea(['rows' => 6]) ?>

      <!-- attribute sig_lock -->
      <?= '' //$form->field($model, 'sig_lock')->textInput() ?>

      <!-- attribute sig_createdat -->
      <?= '' //$form->field($model, 'sig_createdat')->textInput() ?>
      <!-- attribute sig_createdt -->
      <?= '' //$form->field($model, 'sig_createdt')->textInput() ?>
			<!-- attribute sigusr_created_id -->
			<?= '' /*$form->field($model, 'sigusr_created_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(hftadmin\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['sigusr_created_id'])),
    		]
			);*/ ?>

      <!-- attribute sig_updatedat -->
      <?= '' //$form->field($model, 'sig_updatedat')->textInput() ?>
      <!-- attribute sig_updatedt -->
      <?= '' //$form->field($model, 'sig_updatedt')->textInput() ?>
			<!-- attribute sigusr_updated_id -->
			<?= '' /*$form->field($model, 'sigusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(hftadmin\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['sigusr_updated_id'])),
    		]
			);*/ ?>

      <!-- attribute sig_deletedat -->
      <?= '' //$form->field($model, 'sig_deletedat')->textInput() ?>
      <!-- attribute sig_deletedt -->
      <?= '' //$form->field($model, 'sig_deletedt')->textInput() ?>
      <!-- attribute sigusr_deleted_id -->
      <?= '' /*$form->field($model, 'sigusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(hftadmin\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['sigusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
         [
    				'label'   => Yii::t('models', 'Signal'),
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

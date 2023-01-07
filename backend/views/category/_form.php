<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;
use kartik\select2\Select2;
//use kartik\datetime\DateTimePicker;

$getLanguages = GeneralHelper::getLanguages();

/**
* @var yii\web\View $this
* @var backend\models\Category $model
* @var yii\widgets\ActiveForm $form
*/

$catTypes = GeneralHelper::getCategoryTypes();

?>

<div class="category-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Category',
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

			<!-- attribute cat_lock -->
			<?= '' // $form->field($model, 'cat_lock')->textInput() ?>

			<!-- attribute cat_language -->
			<?= $form->field($model, 'cat_language')->dropDownList($getLanguages,
				[
					'prompt' => Yii::t('cruds', 'Select'),
					'disabled' => (isset($relAttributes) && isset($relAttributes['cat_language'])),
				]
			); ?>

			<!-- attribute cat_type -->
      <?= $form->field($model, 'cat_type')->widget(Select2::classname(), [
				'data' => $catTypes,
				'options' => ['prompt' => 'Select a type ...'],
				'pluginOptions' => [
					'allowClear' => true
				],
      ]); ?>

			<!-- attribute cat_title -->
			<?= $form->field($model, 'cat_title')->textInput(['maxlength' => true]) ?>

			<!-- attribute cat_createdt -->
			<?= '' //$form->field($model, 'cat_createdt')->textInput() ?>

			<!-- attribute cat_updatedt -->
			<?= '' //$form->field($model, 'cat_updatedt')->textInput() ?>

			<!-- attribute cat_deletedat -->
			<?= '' //$form->field($model, 'cat_deletedat')->textInput() ?>

			<!-- attribute catusr_deleted_id -->
			<?= '' /*$form->field($model, 'catusr_deleted_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['catusr_deleted_id'])),
    		]
			);*/ ?>

			<!-- attribute cat_description -->
			<?= $form->field($model, 'cat_description')->textarea(['rows' => 6]) ?>

			<!-- attribute cat_remarks -->
			<?= $form->field($model, 'cat_remarks')->textarea(['rows' => 6]) ?>

			<!-- attribute cat_deletedt -->
			<?= '' //$form->field($model, 'cat_deletedt')->textInput() ?>

			<!-- attribute catusr_created_id -->
			<?= '' /*$form->field($model, 'catusr_created_id')->dropDownList(
		    \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['catusr_created_id'])),
    		]
			); */?>

			<!-- attribute catusr_updated_id -->
			<?= '' /*$form->field($model, 'catusr_updated_id')->dropDownList(
    		\yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
    		[
        	'prompt' => Yii::t('cruds', 'Select'),
        	'disabled' => (isset($relAttributes) && isset($relAttributes['catusr_updated_id'])),
    		]
			);*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
    				'label'   => Yii::t('models', 'Category'),
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


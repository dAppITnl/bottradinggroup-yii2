<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \bizley\quill\Quill;
use \common\helpers\GeneralHelper;

$mbrCategories = GeneralHelper::getCategoriesOfType('mbr', false);
//$mbrRoles = GeneralHelper::getMembershipRoles();
$languages = GeneralHelper::getLanguages();
$discordRoles = GeneralHelper::getDiscordRoles();

/**
* @var yii\web\View $this
* @var backend\models\Membership $model
* @var yii\widgets\ActiveForm $form
*/
?>
<p><?=Yii::t('app', 'Keep Groupnr equal for same memberships and different languages'); ?></p>

<div class="membership-form">

  <?php $form = ActiveForm::begin([
    'id' => 'Membership',
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
      <!-- attribute mbr_code -->
      <?= $form->field($model, 'mbr_code')->textInput(['maxlength' => true]) ?>

      <!-- attribute mbr_title -->
      <?= $form->field($model, 'mbr_title')->textInput(['maxlength' => true]) ?>

			<!-- attribute mbr_active -->
			<?= $form->field($model, 'mbr_active')->checkbox(/*['label'=>'']*/); //->textInput() ?>

			<!-- attribute mbr_active4admin -->
			<?= $form->field($model, 'mbr_active4admin')->checkbox(/*['label'=>'']*/); ?>

			<!-- attribute mbr_order -->
			<?= $form->field($model, 'mbr_order')->textInput(['type' => 'number', 'min'=>0]) ?>

			<!-- attribute mbr_groupnr -->
			<?= $form->field($model, 'mbr_groupnr')->textInput() ?>

      <!-- attribute mbr_language -->
      <?= $form->field($model, 'mbr_language')->dropDownList($languages,
        [
					'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['mbr_language'])),
				]
			); ?>

      <!-- attribute mbrcat_id -->
      <?= $form->field($model, 'mbrcat_id')->dropDownList(
        $mbrCategories, //\yii\helpers\ArrayHelper::map(backend\models\Category::find()->all(), 'id', 'cat_title'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['mbrcat_id'])),
        ]
      ); ?>

      <!-- attribute mbr_cardbody -->
      <?= $form->field($model, 'mbr_cardbody')->/*widget(Quill::classname(), [
        'toolbarOptions' => 'FULL', //[['header', 'bold', 'italic', 'underline', 'strike'], ['font'=>[], 'alignment'=>[], 'color'=>[], 'image'=>[]]],
      ]) */textarea(['rows' => 15]) ?>

      <!-- attribute mbr_detailbody -->
      <?= $form->field($model, 'mbr_detailbody')->/*widget(Quill::classname(), [
        'toolbarOptions' => 'FULL', //[['header', 'bold', 'italic', 'underline', 'strike'], ['font'=>[], 'alignment'=>[], 'color'=>[], 'image'=>[]]],
      ])*/textarea(['rows' => 15]) ?>

      <!-- attribute usr_discordroles -->
      <?= $form->field($model, 'mbr_discordroles')->dropDownList($discordRoles,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]); ?>

			<!-- attribute mbr_discordlogchanid -->
			<?= $form->field($model, 'mbr_discordlogchanid')->textInput(['maxlength' => true]) ?>

      <!-- attribute mbr_remarks -->
      <?= $form->field($model, 'mbr_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute mbr_lock -->
      <?= '' //$form->field($model, 'mbr_lock')->textInput() ?>

			<!-- attribute mbr_createdt -->
			<?= '' //$form->field($model, 'mbr_createdt')->textInput() ?>
      <!-- attribute mbr_createdat -->
      <?= '' //$form->field($model, 'mbr_createdat')->textInput() ?>
      <!-- attribute mbrusr_created_id -->
      <?= '' /*$form->field($model, 'mbrusr_created_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['mbrusr_created_id'])),
        ]
      );*/ ?>

      <!-- attribute mbr_updatedt -->
      <?= '' //$form->field($model, 'mbr_updatedt')->textInput() ?>
      <!-- attribute mbr_updatedat -->
      <?= '' //$form->field($model, 'mbr_updatedat')->textInput() ?>
      <!-- attribute mbrusr_updated_id -->
      <?= '' /*$form->field($model, 'mbrusr_updated_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['mbrusr_updated_id'])),
        ]
      );*/ ?>

			<!-- attribute mbr_deletedt -->
			<?= '' //$form->field($model, 'mbr_deletedt')->textInput() ?>
      <!-- attribute mbr_deletedat -->
      <?= '' //$form->field($model, 'mbr_deletedat')->textInput() ?>
      <!-- attribute mbrusr_deleted_id -->
      <?= '' /*$form->field($model, 'mbrusr_deleted_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(backend\models\User::find()->all(), 'id', 'id'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['mbrusr_deleted_id'])),
        ]
      );*/ ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
						'label'   => Yii::t('models', 'Membership'),
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


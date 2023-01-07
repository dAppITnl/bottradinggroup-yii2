<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

$siteLevels = GeneralHelper::getSiteLevels(false);
$siteCssfiles = GeneralHelper::getSiteCssFiles();
$languages = GeneralHelper::getLanguages();

/**
* @var yii\web\View $this
* @var backend\models\User $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="user-form">

  <?php $form = ActiveForm::begin([
    'id' => 'User',
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
			<!-- attribute username -->
			<?= $form->field($model, 'username')->textInput(['maxlength' => true, 'readonly' => (!$model->isNewRecord)]) ?>

			<!-- attribute usr_sitelevel -->
			<?= $form->field($model, 'usr_sitelevel')->dropDownList( $siteLevels
				/*backend\models\User::optsusrsitelevel()*/
			); ?>

      <!-- attribute usr_sitecsstheme -->
      <?= $form->field($model, 'usr_sitecsstheme')->dropDownList( $siteCssfiles
        /*backend\models\User::optsusrsitelevel()*/
      ); ?>

			<!-- attribute usr_password -->
			<?= '' // $form->field($model, 'usr_password')->textInput(['maxlength' => true]) ?>

			<!-- attribute password_hash -->
			<?= '' // $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

			<!-- attribute auth_key -->
			<?= '' //$form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

      <!-- attribute password_reset_token -->
      <?= '' //$form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

      <!-- attribute verification_token -->
      <?= '' //$form->field($model, 'verification_token')->textInput(['maxlength' => true]) ?>

      <!-- attribute status -->
      <?= '' //$form->field($model, 'status')->textInput() ?>

			<!-- attribute email -->
			<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

			<!-- attribute usr_firstname -->
			<?= $form->field($model, 'usr_firstname')->textInput(['maxlength' => true]) ?>

			<!-- attribute usr_lastname -->
			<?= $form->field($model, 'usr_lastname')->textInput(['maxlength' => true]) ?>

			<!-- attribute usr_countrycode -->
			<?= $form->field($model, 'usr_countrycode')->textInput(['maxlength' => true]) ?>

      <!-- attribute usr_language -->
      <?= $form->field($model, 'usr_language')->dropDownList($languages,
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['usr_language'])),
        ]
      ); ?>

			<!-- attribute usr_signalactive -->
			<?= '' //$form->field($model, 'usr_signalactive')->textInput() ?>

      <!-- attribute usr_moralisid -->
      <?= $form->field($model, 'usr_moralisid')->textInput(['maxlength' => true]) ?>

			<!-- attribute usr_discordusername -->
			<?= $form->field($model, 'usr_discordusername')->textInput(['maxlength' => true, 'readonly' => true]) ?>
			<!-- attribute usr_discordnick -->
			<?= $form->field($model, 'usr_discordnick')->textInput(['maxlength' => true, 'readonly' => true]) ?>
			<!-- attribute usr_discordid -->
      <?= $form->field($model, 'usr_discordid')->textInput(['type' => 'number', 'step' => '1', 'readonly' => true]) ?>
      <!-- attribute usr_discordroles -->
      <?= $form->field($model, 'usr_discordroles')->textarea(['rows' => 6, 'readonly' => true]) ?>
			<!-- attribute usr_discordjoinedat -->
			<?= $form->field($model, 'usr_discordjoinedat')->textInput(['disabled' => true, 'readonly' => true]) ?>

      <!-- attribute usr_remarks -->
      <?= $form->field($model, 'usr_remarks')->textarea(['rows' => 6]) ?>


      <!-- attribute usr_lock -->
      <?= '' //$form->field($model, 'usr_lock')->textInput() ?>

			<!-- attribute usr_createdt -->
			<?= '' //$form->field($model, 'usr_createdt')->textInput() ?>

			<!-- attribute usr_updatedt -->
			<?= '' //$form->field($model, 'usr_updatedt')->textInput() ?>

			<!-- attribute deleted_at -->
			<?= '' //$form->field($model, 'deleted_at')->textInput() ?>

			<!-- attribute deleted_by -->
			<?= '' //$form->field($model, 'deleted_by')->textInput() ?>

			<!-- attribute usr_deletedt -->
			<?= '' //$form->field($model, 'usr_deletedt')->textInput() ?>
    </p>
    <?php $this->endBlock(); ?>

    <?= Tabs::widget(
      [
        'encodeLabels' => false,
        'items' => [
          [
    				'label'   => Yii::t('models', 'User'),
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

		<p><hr></p>

    <p><?= Yii::t('app', 'You can ') . Html::a(Yii::t('app', 'reset your password here'), ['site/request-password-reset']) ?>.
    <br>
    <?= Yii::t('app', 'Need new verification email?') . ' ' . Html::a(Yii::t('app', 'Resend'), ['site/resend-verification-email']) ?></p>

  </div>

</div>


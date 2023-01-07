<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\ContactForm */
use Yii;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\captcha\Captcha;

$contactKinds = common\helpers\GeneralHelper::getContactKinds();

$this->title = Yii::t('app', 'Contact');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-contact">
	<div class="row">
		<div class="container d-flex justify-content-center">
      <div class="text-left">
				<h1><?= Html::encode($this->title) ?></h1>

				<p><!-- If you have business inquiries or other questions, please fill out the following form to contact us. Thank you. -->
					<?= Yii::t('app', 'For questions or comments, first of all, we refer you to our Discord server, where our community may be able to help you faster, but you can also contact us via this form!') ?>
				</p>

				<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

				<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

				<?= $form->field($model, 'email') ?>

				<?= $form->field($model, 'kind')->widget(Select2::classname(), [
					'data' => $contactKinds,
					'options' => ['prompt' => Yii::t('app', 'Select kind ...')],
					'pluginOptions' => [
						'allowClear' => true
					],
				]); ?>

				<?= $form->field($model, 'subject') ?>

				<?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

				<?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
				<?= '' /*$form->field($model, 'verifyCode')->widget(Captcha::className(), [
					'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
				])*/ ?>

				<div class="form-group">
					<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>

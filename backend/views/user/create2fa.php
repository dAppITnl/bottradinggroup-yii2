<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

/**
* @var yii\web\View $this
* @var backend\models\User $model
*/

$this->title = Yii::t('models', 'User - add 2FA');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud user-add2fa">

	<h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'User') ?></small></h1>

	<?php if (!empty($model->_inlineurl2fa)) : ?>
	<h2><?= Yii::t('app', 'The created 2FA:') ?></h2>
	<p><?= Yii::t('app', 'Scan this QR-code to your 2FA Authenticator as a new code. Each time you open this screen a new code is created.') ?></p>
	<p><img src="<?= $model->_inlineurl2fa ?>"><br><?= Yii::t('app', '2FA code: ') .'<b>'. $model->usr_2fasecret .'</b>' ?></p>
	<?php endif; ?>

	<div class="user-form">
		<?php $form = ActiveForm::begin([
			'id' => 'User2FA',
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
		<h3><?= Yii::t('app', 'Enter the generated key by the authenticator') ?></h3>
    <p>
			<!-- attribute username -->
      <?= $form->field($model, '_authenticatorreply')->textInput(['maxlength' => true]) ?>
 		</p>

 		<hr/>

 		<?php echo $form->errorSummary($model); ?>

 		<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('cruds', 'Check'),
			[
     		'id' => 'save-' . $model->formName(),
     		'class' => 'btn btn-success'
   		]
  	); ?>
  	<?php ActiveForm::end(); ?>

		<p>
			<?= '' //Html::a(Yii::t('app', 'Back'), yii\helpers\Url::previous(), ['class' => 'btn btn-primary']) ?>
		</p>

  	</div>
	</div>
</div>


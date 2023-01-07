<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use richardfan\widget\JSRegister;

$this->title = 'Login';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields to login:</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, /*'onBlur'=>'check42fa(this.value)'*/]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

						<div id="check2fa" style="display: none;">
						<?= $form->field($model, 'check2fa')->textInput([]) ?>
						</div>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

						<?= $form->errorSummary($model); ?>

						<div style="color:#999;margin:1em 0">
               <?= Yii::t('app', 'If you forgot your password you can ') . Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']) ?>.
               <br>
               <?= Yii::t('app', 'Need new verification email?') . ' ' . Html::a(Yii::t('app', 'Resend'), ['site/resend-verification-email']) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php JSRegister::begin([
//    'key' => 'check2fa-login',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
$('#loginform-username').on('change blur input', function() {
	try {
		var username = $(this).val();
		console.log('username=['+username+']');
		if (username.length>0) {
			$.post(
				'/site/check42fa',
				{'username':username}, (response) => {
					var data=JSON.parse(response);
					if (data.check42fa == 'true') {
						$('#loginform-check2fa').val("")
						$('#check2fa').show();
					} else {
						$('#loginform-check2fa').val("*");
						$('#check2fa').hide();
					}
				}
			);
		}
	} catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
	}
});

//$(document).ready( function() {
	$('#loginform-username').val("");
	$('#loginform-password').val("");
	$('#loginform-check2fa').val("*");
//});
</script>
<?php JSRegister::end(); ?>


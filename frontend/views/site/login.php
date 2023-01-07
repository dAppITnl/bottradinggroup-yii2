<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

//use Yii;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use richardfan\widget\JSRegister;

$dev = (in_array(Yii::$app->getRequest()->getUserIP(), Yii::$app->params['web3Auth_whitelistIPs']));
?>
<div class="site-login">
	<div class="body-container">

		<div class="container" c="d-flex justify-content-center">
			<div class="row mt-4">
        <div class="col col-7 mx-auto text-left"><?= ($dev ? ".":"") ?>
					<div class="card login-cards">
						<div class="card-body">
							<div class="table-card-head"><h2 class="center"><?= Yii::t('app', 'Login') ?></h2></div>
							<div class="table-card-content">
								<?php if (true || !$dev) : ?>
								<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
								<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
								<?= $form->field($model, 'password')->passwordInput() ?>
								<?= $form->field($model, 'usr_moralisid')->hiddenInput(['value'=>''])->label(false) ?>
								<div id="check2fa" style="display: none;">
									<?= $form->field($model, 'check2fa')->textInput([]) ?>
								</div>
								<?= $form->field($model, 'rememberMe')->checkbox() ?>
								<div style="color:#999;margin:1em 0">
									<?= Yii::t('app', 'If you forgot your password you can ') . Html::a(Yii::t('app', 'reset it'), ['site/request-password-reset']) ?>.<br>
									<?= Yii::t('app', 'Need new verification email?') . ' ' . Html::a(Yii::t('app', 'Resend'), ['site/resend-verification-email']) ?>
								</div>
								<?= $form->errorSummary($model); ?>
								<div class="form-group">
									<?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
								</div>
								<?php ActiveForm::end(); ?>
								<?php else : ?>
									<button type="button" id="signup-moralis-authenticate">Login</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	$('#loginform-usr_moralisid').val("");
//});

$('#login-form').submit( async function(event) {
	event.preventDefault();
	var _this = $(this);
	const username = $('#loginform-username').val();
	const password = $('#loginform-password').val();
	//console.log('username='+username+' password='+password);
	if (username !== '' && password !== '') {
		console.log('Moralis login: '+username);
		try {
			result = await Moralis.User.logIn(username, password, {usePost: true});
			console.log('Moralis: ', result);
			$('#loginform-usr_moralisid').val(result.id);
		} catch (error) {
    	submit=false;
    	console.error('Moralis error: '+error.code+'='+error.message);
			// effectively skipped.. ToDo!
		}
	}
	_this.unbind('submit').submit();
});

<?php if (false && $dev) : ?>
/*
 * Authenticate via Moralis - Web3Auth method
 */
$('#signup-moralis-authenticate').on('click', async function(event) {
	<?php $clientID=Yii::$app->params['web3Auth_clientID']; if (!empty($clientID)) : ?>
	console.log('auth clientId='+'<?= $clientID ?>');
	const result = await Moralis.authenticate({
		provider: "web3Auth",
		clientId: "<?= $clientID ?>",
		appLogo: "https://bottradinggroup.nl/images/btgnl_Test.png",
		<?php $methods=Yii::$app->params['web3Auth_loginMethods']; echo (!empty($methods) ? 'loginMethodsOrder: '.$methods.',' : ''); ?>
		<?php $chainID=Yii::$app->params['web3Auth_chainId'];      echo (!empty($chainID) ? 'chainId: '.$chainID.',' : ''); ?>
		<?php $theme  =Yii::$app->params['web3Auth_theme'];        echo (!empty($theme)   ? 'theme: "'.$theme.'",' : ''); ?>
	});
	console.log('auth result:', result);
	alert('Result id='+result.id);
	<?php else : ?>
		alert('No ClientID given!');
	<?php endif ?>
});
<?php endif ?>
</script>
<?php JSRegister::end(); ?>


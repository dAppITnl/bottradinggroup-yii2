<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use Yii;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use richardfan\widget\JSRegister;
//use yii\helpers\Html;

?>
<div class="site-signup">
	<div class="body-content">

		<div class="container">

			<div class="row mt-4">
				<div class="col col-7 mx-auto text-left">
					<div class="card login-cards">
            <div class="card-body">
              <div class="table-card-head"><h2 class="center"><?= Yii::t('app', 'Signup') ?></h2></div>
              <div class="table-card-content">
				    	  <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
  	  			    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    	  			  <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
								<?= $form->field($model, 'password')->passwordInput() ?>

								<?= $form->field($model, 'discordusername')->textInput(['maxlength' => true]) ?>
								<?= $form->field($model, 'discordid')->textInput(['maxlength' => true]) ?>
								<?= $form->field($model, 'discordnick')->hiddenInput(['value'=>''])->label(false) ?>
								<?= $form->field($model, 'discordjoinedat')->hiddenInput(['value'=>''])->label(false) ?>
								<?= $form->field($model, 'discordroles')->hiddenInput(['value'=>''])->label(false) ?>
								<div id="discordError"></div>

								<?= $form->field($model, 'morid')->hiddenInput(['value'=>''])->label(false) ?>

								<?= $form->field($model, 'accept_disclaimer')->checkbox()->label(
									 Yii::t('app', 'I accept the {disclaimerlink}.', ['disclaimerlink' => Html::a(Yii::t('app', 'disclaimer'), ['/site/disclaimer'], ['target'=>'disclaimer'])])
								) ?>
								<?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
      			  	<div class="form-group">
			          	<?= Html::submitButton(Yii::t('app', 'Signup'), ['id'=>'signup-button', 'name' => 'signup-button', 'class' => 'btn btn-success']) ?>
									<div id='submitError' class='invalid-feedback'></div>
								</div>
								<?php ActiveForm::end(); ?>
							</div>
						</div>
					</div>
        </div>
			</div>

		</div>
	</div>
</div>

<?php JSRegister::begin([
    'key' => 'moralis-signup',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
$('#signup-button').on('click', async function(event) {
  event.preventDefault();
  var submit=true;
  try {
  	const username = document.getElementById("signupform-username").value;
    const email = document.getElementById("signupform-email").value;
    const password = document.getElementById("signupform-password").value;
    await moralis_signup(username, email, password);
		const userDetails = moralis_getUserDetails();
		console.log('userDetails: ', userDetails);
    if (userDetails.uid) { $('#signupform-morid').value(userDetails.uid); }
  } catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
    $('#submitError').text('');
    if (error.message.indexOf('email address')) {
      $('#signupform-email').addClass('is-invalid').removeClass('is-valid').attr('aria-invalid', true).nextAll('div.invalid-feedback').first().text('<?= Yii::t('app', 'Email already exists') ?>');
    } else if (error.message.indexOf('user')) {
       $('#signupform-username').addClass('is-invalid').removeClass('is-valid').attr('aria-invalid', true).nextAll('div.invalid-feedback').first().text('<?= Yii::t('app', 'Username already exists') ?>');
    } else {
       $('#submitError').text(error.message);
    }
    //$('#signupform-username').focus();
  }
  console.log('beforesubmit is ' + (submit ? 'YES':'NO'));
  if (submit) $('#form-signup').submit();
});

// ---

function getDiscordData() {
	try {
		$('#discordError').text('');
		const id = $('#signupform-discordid').val();
		const username = $('#signupform-discordusername').val();
		console.log('getDiscordData: id='+id+' username='+username);
		$.post(
			'/site/getdiscorddata',
			{'id':id, 'username':username},
			(response) => {
				var data = JSON.parse(response);
				console.log('getDiscordData data: ',data);
				if (data) {
					$('#signupform-discordusername').val(data.username);
					$('#signupform-discordnick').val(data.nick);
					$('#signupform-discordid').val(data.id);
					$('#signupform-discordjoinedat').val(data.joinedat);
					$('#signupform-discorroles').val(data.roles.join(','));

					$('#discordError').text(' => ' + ((typeof data.error !== 'undefined') ? data.error : "<?= Yii::t('app', 'Found discord user, please check if it is yours.') ?>"));
				} else {
					$('#discordError').text(" => <?= Yii::t('app','User unknown at Discord') ?>");
				}
			}
		);
	} catch (error) {
		const msg = error.message + ' (' + error.code + ')';
		console.error('getDiscordData error: '+msg);
		$('#discordError').text(msg);
	}
};

$('#signupform-discordid').on('blur', function() { getDiscordData(); });
$('#signupform-discordusername').on('blur', function() { getDiscordData(); });

</script>
<?php JSRegister::end(); ?>

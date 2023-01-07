<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

$languages = GeneralHelper::getLanguages();
$siteThemes = GeneralHelper::getSiteCssFiles();

/**
* @var yii\web\View $this
* @var backend\models\User $userModel
* @var yii\widgets\ActiveForm $form
*/

?>
<div class="user-update">
  <div class="body-content">

    <div class="container">

      <div class="row">
				<div class="col text-center">
					<p><h1><?= Yii::t('app', 'Update my profile') ?></h1></p>
				</div>
			</div>
			<div class="row">
				<div class="col col-10 text-left">
					<p class="text-secondary"><small><?= Yii::t('app', '(Note: not all fields are allowed to change. Discord fields are checked with the Discord server after enter of username (or nick) or the Id.') ?></small></p>
				</div>
			</div>

			<div class="row">
				<div class="col col-9 mx-auto text-left">
					<div class="user-update-form">

					  <?php $form = ActiveForm::begin([
							'id' => 'userUpdate',
							'layout' => 'horizontal',
							'enableClientValidation' => true,
							'errorSummaryCssClass' => 'error-summary alert alert-danger',
							'fieldConfig' => [
								'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
								'horizontalCssClasses' => [
									'label' => 'col-sm-4',
									#'offset' => 'col-sm-offset-4',
									'wrapper' => 'col-sm-8',
									'error' => '',
									'hint' => '',
								],
							],
						]); ?>

						<!-- h3 class="col-sm-8"><?= Yii::t('app', 'Profile') ?>:</h3 -->

						<?= $form->field($userModel, 'username')->textInput(['maxlength' => true, 'readonly' => true /*(!$userModel->isNewRecord)*/]) ?>

      			<?= $form->field($userModel, 'email')->textInput(['maxlength' => true]) ?>

						<?= $form->field($userModel, 'usr_firstname')->textInput(['maxlength' => true]) ?>

						<?= $form->field($userModel, 'usr_lastname')->textInput(['maxlength' => true]) ?>

						<?= $form->field($userModel, 'usr_countrycode')->textInput(['maxlength' => true]) ?>

			      <?= $form->field($userModel, 'usr_language')->dropDownList($languages,
        			[
          			'prompt' => Yii::t('cruds', 'Select'),
          			'disabled' => (isset($relAttributes) && isset($relAttributes['usr_language'])),
        			]
      			); ?>

      			<?= '' /* $form->field($userModel, 'usr_sitecsstheme')->dropDownList($siteThemes,
        			[
          			'prompt' => Yii::t('cruds', 'Select'),
          			'disabled' => (isset($relAttributes) && isset($relAttributes['usr_sitecsstheme'])),
        			]
      			); */ ?>

      			<?= $form->field($userModel, 'usr_remarks')->textarea(['rows' => 3]) ?>

						<h3 class="col-sm-8"><?= Yii::t('app', 'Discord') ?>:</h3>
      			<?= '' // $form->field($userModel, 'usr_moralisid')->textInput(['maxlength' => true, 'readonly'=>true]) ?>

      			<?= $form->field($userModel, 'usr_discordusername')->textInput(['maxlength' => true]) ?>

			      <?= $form->field($userModel, 'usr_discordnick')->textInput(['maxlength' => true, 'readonly' => true]) ?>

      			<?= $form->field($userModel, 'usr_discordid')->textInput(['type' => 'number', 'step' => '1']) ?>

      			<?= $form->field($userModel, 'usr_discordjoinedat')->textInput(['readonly' => true]) ?>

      			<?= $form->field($userModel, 'usr_discordroles')->hiddenInput() ?>
						<div class="col-sm-8 mt-n3">
							<textarea id="discordRoles" class="form-control" rows="5" readonly><?= $discordRoles ?></textarea>
						</div>

						<p><div id="MemberError" class="col-sm-8"></div></p>

						<h3 class="col-sm-8"><?= Yii::t('app', 'Crypto') ?>:</h3>
						<?= $form->field($userModel, 'usr_moralisid')->textInput(['maxlength' => true, 'readonly'=>true]) ?>

    				<p><?php echo $form->errorSummary($userModel); ?></p>

						<br>

						<?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('cruds', 'Save'),
      				[
        				'id' => 'save-' . $userModel->formName(),
        				'class' => 'btn btn-success'
      				]
    				); ?>
						<?= Html::a(Yii::t('app', 'Cancel'), ['/user/index'], ['class'=>'btn btn-primary']) ?>

    				<?php ActiveForm::end(); ?>

						<p><hr></p>

						<p><?= Yii::t('app', 'You can {link:resetpw}reset your password here{/link}', ['link:resetpw' => '<a href="/site/request-password-reset">', '/link' => '</a>']) ?>
    				<br>
    				<?= Yii::t('app', 'Need new verification email?') . ' ' . Html::a(Yii::t('app', 'Resend'), ['site/resend-verification-email']) ?></p>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php JSRegister::begin([
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
function getDiscordMember(id, username) {
  $('#MemberError').text('');
  $.post(
    '/user/getdiscordmember',
    {'uid':'<?= $userModel['id'] ?>', 'did':id, 'username':username},
    (response) => {
      var data=JSON.parse(response);
      console.log('data: ',data);
      if (data) {
        $('#user-usr_discordusername').val(data.username);
        $('#user-usr_discordnick').val(data.nick);
        $('#user-usr_discordid').val(data.id);
        $('#user-usr_discordjoinedat').val(data.joinedat);
				$('#user-usr_discordroles').val(data.roles);
				$('#discordRoles').val(data.rolenames);

        $('#MemberError').text(' => ' + ((typeof data.error !== 'undefined') ? data.error : "<?= Yii::t('app', 'Found discord user, please check if it is yours.') ?>"));
      } else {
        $('#MemberError').text(" => <?= Yii::t('app','User unknown at Discord') ?>");
      }
    }
  );
}

$('#user-usr_discordusername').on('blur', function(event) {
  event.preventDefault();
  getDiscordMember(0, $(this).val());
});

$('#user-usr_discordid').on('blur', function(event) {
  event.preventDefault();
  getDiscordMember($(this).val(), '');
});

/*
async function CheckMoralis() {
	var moralisId = ''+$('#user-usr_moralisid').val();
	var email = ''+$('#user-email').val();
}
*/

</script>
<?php JSRegister::end(); ?>


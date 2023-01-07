<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

$siteLevels = GeneralHelper::getSiteLevels(false);
$languages = GeneralHelper::getLanguages();

$discordRoles = GeneralHelper::getDiscordRoles();
$disabledDiscordRoles = GeneralHelper::disabledDiscordRoles();

$this->title = Yii::t('models', 'User details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$userModel->id, 'url' => ['view', 'id' => $userModel->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Details');

?>
<div class="user-details">
  <h1><?= Yii::t('app', "General & Discord data for '{username}'", ['username' => Html::encode($userModel->username)]) ?>:</h1>

	<p><?= Html::a(Yii::t('app', 'To user details'), ['/user/userdetail?id='.$userModel->id]) ?></p>

	<?= DetailView::widget([
		'model' => $userModel,
    'attributes' => [
			'email',
      [
        'format' => 'html',
        'attribute' => 'usr_language',
        'value' => $languages[$userModel->usr_language],
      ],
      [
        'attribute'=>'usr_sitelevel',
        'value' => $siteLevels[ $userModel->usr_sitelevel ],
      ],
			'usr_discordusername',
			'usr_discordnick',
      'usr_discordid',
			/*[
				'attribute'=>'usr_discordroles',
        'value' => implode(', ', $userModel->usr_discordroles),
			],*/
      [
        'attribute'=>'usr_discordroles',
        'value' => function($userModel) use ($discordRoles) {
					$result = [];
					if (is_array($userModel->usr_discordroles)) foreach($userModel->usr_discordroles as $role) $result[] = $discordRoles[ $role ];
					return implode(', ',$result);
				}
      ],
			'usr_discordjoinedat',
		],
	]); ?>

	<hr>

	<h3><?= Yii::t('app', "Update user's Discord") ?>:</h3>

	<div class="user-setdiscordtoken">

    <?php $form = ActiveForm::begin([
      'id' => 'Usermodel',
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
      <p>
      <!-- attribute usr_discordusername -->
      <?= $form->field($userModel, 'usr_discordusername')->textInput(['maxlength' => true]) ?>

			<!-- attribute usr_discordnick -->
			<?= $form->field($userModel, 'usr_discordnick')->textInput(['maxlength' => true, 'readonly' => true]) ?>

      <!-- attribute usr_discordid -->
      <?= $form->field($userModel, 'usr_discordid')->textInput(['type' => 'number', 'step' => '1']) ?>

			<!-- attribute usr_discordjoinedat -->
			<?= $form->field($userModel, 'usr_discordjoinedat')->textInput(['readonly' => true]) ?>

      <!-- attribute usr_discordroles -->
      <?= '' /* $form->field($userModel, 'usr_discordroles')->dropDownList($discordRoles,
        ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', /*'options'=>$disabledDiscordRoles*/ /*]);*/ ?>

			<?= $form->field($userModel, 'usr_discordroles')->checkboxList($discordRoles, ['separator' => '<!-- br -->']); ?>
      </p>

      <hr/>

      <?php echo $form->errorSummary($userModel); ?>

      <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('app', 'Save & update Roles at Discord'),
        ['id' => 'save-' . $userModel->formName(),  'class' => 'btn btn-success']
      ); ?>

			<?= Html::a(Yii::t('app', 'Cancel'), yii\helpers\Url::previous(), ['class'=>'btn btn-primary']) ?>

			<p><hr></p>

			<p>
				<button id="checkUsername" class="btn btn-primary"><?= Yii::t('app', 'Search username (or nick as username)') ?></button> or
				<button id="checkDiscordid" class="btn btn-primary"><?= Yii::t('app', 'Search Discord Id') ?></button> <?= Yii::t('app', 'at discord server and show to update user') ?><br>
				<span id="MemberError" class="text-red"></span>
			</p>

      <?php ActiveForm::end(); ?>
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
		{'id':id, 'username':username},
		(response) => {
			var data=JSON.parse(response);
			console.log('data: ',data);
			if (data) {
				$('#user-usr_discordusername').val(data.username);
				$('#user-usr_discordnick').val(data.nick);
				$('#user-usr_discordid').val(data.id);
				$('#user-usr_discordjoinedat').val(data.joinedat);

				/*$.each( $('#user-usr_discordroles option:selected'), function() { $(this).prop('selected', false); });
				if (data.roles) {
					data.roles.forEach((role, i) => {
						console.log('role '+i+' = '+role);
						$("#user-usr_discordroles option[value='"+role+"']").prop('selected', true);
					});
				}*/

				$('input[type=checkbox][name=User\\[usr_discordroles\\]\\[\\]]').each(function() {
					console.log('cb='+this.value);
					this.checked = data.roles.includes(this.value);
				});

				$('#MemberError').text(' => ' + ((typeof data.error !== 'undefined') ? data.error : 'Ok'));
			} else {
				$('#MemberError').text(" => <?= Yii::t('app','User unknown at Discord') ?>");
			}
		}
	);
}


$('#checkUsername').on('click', function(event) {
	event.preventDefault();
	const username = $('#user-usr_discordusername').val();
	getDiscordMember(0, username);
});

$('#checkDiscordid').on('click', function(event) {
	event.preventDefault();
	const id = $('#user-usr_discordid').val();
	getDiscordMember(id, '');
});

</script>
<?php JSRegister::end(); ?>


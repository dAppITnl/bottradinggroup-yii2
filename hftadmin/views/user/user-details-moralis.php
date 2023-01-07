<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
//use backend\models\Userpay;
//use backend\models\Userbot;
use common\helpers\GeneralHelper;
//use backend\models\Usermember;

//use yii\bootstrap4\Html;
use richardfan\widget\JSRegister;

use backend\assets\MoralisAsset;

//$yesNos = GeneralHelper::getYesNos(false);

$this->title = Yii::t('models', 'User Moralis details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$userModel->id, 'url' => ['view', 'id' => $userModel->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Details');
?>
<div class="user-details-moralis">

  <div class="row">
    <div class="col">
      <h2>Moralis details of user: <?= HTML::a($userModel->username, ['/user/view?id='.$userModel->id]) ?></h2>
    </div>
  </div>

	<div class="row">
		<div class="col">
			<table border=1 width="100%">
				<tr><th>userId:</th><td><span id="userId"></span></td></tr>
				<tr><th>username:</th><td><span id="userName"></span></td></tr>
				<tr><th>email:</th><td><span id="email"></span></td></tr>
				<tr><th>ethAddress:</th><td><span id="ethAddress"></span></td></tr>
				<tr><th>error:</th><td><span id="error"></span></td></tr>
			</table>
		</div>
	</div>

</div>

<?php
	MoralisAsset::register($this);
	JSRegister::begin([
    'key' => 'moralis-functions',
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
async function getMoralisdata() {

/*	const currentUser = await Moralis.User.current();
	if (currentUser) {
		// do stuff with the user
		console.llog('currentUser: ',currentUser);
		Moralis.User.logOut();

	} else {
		// show the signup or login page
		console.log('No currentUser!');
	}
*/
	const userId = "<?= $userModel->usr_moralisid ?>";
	var userData = { 'error': 'Unknown MoralisId' };
	if (userId != "") {
		const params =  { userId: "<?= $userModel->usr_moralisid ?>" };
		console.log('params: '+JSON.stringify(params));
		userData = await Moralis.Cloud.run("getUserdata", params);
		console.log('userdata: ',userData);
		console.log('id='+userData.userId+' error='+userData.error+' username='+userData.username+' email='+userData.email+' ethAddress='+userData.ethAddress);
	}
	$('#userId').text(userData.userId);
	$('#userName').text(userData.username);
	$('#email').text(userData.email);
	$('#ethAddress').text(userData.ethAddress);
	$('#error').text(userData.error);
	

}
getMoralisdata();

</script>
<?php JSRegister::end(); ?>

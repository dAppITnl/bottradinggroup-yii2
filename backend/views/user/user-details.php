<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use backend\models\Userpay;
use backend\models\Userbot;
use common\helpers\GeneralHelper;
use backend\models\Usermember;

$yesNos = GeneralHelper::getYesNos(false);

Yii::trace('** view userdetail usermembersData: '.print_r($usermembersData, true));

$this->title = Yii::t('models', 'User details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$userModel->id, 'url' => ['view', 'id' => $userModel->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Details');
?>
<div class="user-details">

	<div class="row">
		<div class="col">
			<h2>User: <?= HTML::a($userModel->username, ['/user/view?id='.$userModel->id]) ?></h2>
			<p><?= Html::a(Yii::t('app', 'To Discord settings'), ['/user/setdiscordtoken?id='.$userModel->id]) ?></p>
		</div>
	</div>

	<?php if (!empty($usermembersData)) :
					include('usermember-details.php');
				else :
					echo Yii::t('app', 'No usermember details found');
				endif;
	?>
</div>

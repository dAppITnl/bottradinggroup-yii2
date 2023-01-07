<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use frontend\models\Userpay;
use frontend\models\Cryptoaddress;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

$depositData = $cryptoData['depositInstructions'];

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="userpay-pay">
  <div class="body-content">
    <div class="container">

    <div class="row mt-4">
      <div class="col col-mt-12 text-left">
        <h3><?= Yii::t('app', "Pay details") ?>:</h3>
      </div>
    </div>

    <div class="row">
      <div class="col">
				<table with="100%">
					<tr><td><?= Yii::t('app', 'Amount') ?></td><td><?= $depositData['amount'] .' '. $depositData['assetCode'] ?></td></tr>
					<tr><td><?= Yii::t('app', 'Pay to address') ?></td><td><?= $depositData['address'] ?></td></tr>
					<?php if (!empty($depositData['memo'])) : ?>
						<tr><td><?= Yii::t('app', 'with memo') ?></td><td><?= $depositData['memo'] ?></td></tr>
						<tr><td><?= Yii::t('app', 'memo type') ?></td><td><?= $depositData['memoType'] ?></td></tr>
					<?php endif; ?>
					<?php if (!empty($depositData['destinationTag'])) : ?>
						<tr><td><?= Yii::t('app', 'Destination Tag') ?></td><td><?= $depositData['destinationTag'] ?></td></tr>
					<?php endif; ?>
					<tr><td><?= Yii::t('app', 'Pay before') ?></td><td><?= $cryptoData['expirationTime'] ?></td></tr>
				</table>
			</div>
		</div>

		<hr>

		<?= Html::a(Yii::t('app', 'Payment done'), ['/membership/paydone?ref='.$payReference.'&id=',$cryptoData['checkoutId']], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('app', 'cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-primary']) ?>
  </div>
</div>

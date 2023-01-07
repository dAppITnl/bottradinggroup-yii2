<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use \common\helpers\GeneralHelper;
use frontend\models\Userpay;

$umPrice = $userpayModel->upy_payamount;
$pricelistPeriods = GeneralHelper::getPricelistPeriods();

$success = $userpayModel->upy_state == Userpay::UPY_STATE_PAID;
$tierName = Html::encode($userpayModel->upymbr->mbr_title);

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="userpay-paydone">
  <h1><?= ($success ? Yii::t('app', 'Your payment was successful') : Yii::t('app', 'Your payment is cancelled')); ?></h1>

  <h3 class="mt-4"><?= Yii::t('app', 'Your order'); ?>:</h3>
  <?= DetailView::widget([
    'model' => $userpayModel,
    'attributes' => [
			[
        'format' => 'raw',
        'label' => Yii::t('app', 'Subscription'),
        'value' => $userpayModel->upymbr->mbr_title,
      ],
      [
        'format' => 'raw',
        'label' => Yii::t('app', 'Period'),
        'value' => $pricelistPeriods[ $userpayModel->upyumb->umbprl->prl_percode ],
      ],
      'upy_startdate',
      'upy_enddate',
      [
        'format' => 'html',
        'attribute' => 'upy_payamount',
        'value' => $userpayModel->upysymPay->sym_html . ' ' . $umPrice
									. (!empty($userpayModel->upy_discountcode && (substr($userpayModel->upy_discountcode,-9)!='(invalid)')) ? ' '.Yii::t('app', '(discount)') : ''),
      ],
    ],
  ]); ?>

  <hr>

	<?php if (!$success) : ?>
		<p><?= Yii::t('app', 'You can {link:restart}restart the payment{/link}', ['link:restart' => '<a href="/membership/pay?id='.$userpayModel->upyumb_id.'" class="btn btn-info">', '/link' => '</a>']) ?></p>
	<?php else : ?>
		<p><?= Yii::t('app', 'Return to {link:returnto}overview{/link}', ['link:returnto' => '<a href="/membership" class="btn btn-success">', '/link' => '</a>']) ?>
	<?php endif ?>


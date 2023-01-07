<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use \common\helpers\GeneralHelper;

//$userId = Yii::$app->user->id;
//$userIdentity = Yii::$app->user->identity;

//$signals = \frontend\models\Signal::getSignalsForUserBotsignal();

?>
<div class="site-index">
  <div class="body-content">

    <div class="row">
      <div class="col text-center">
        <h3 class=""><?= Yii::t('app', 'Welcome {username}', ['username'=>Yii::$app->user->identity->username]) ?></h3>
				<h1 class=""><b><?= Yii::t('app', 'What are you waiting for?') ?></b> <span class="grey-text"><?= Yii::t('app', 'Start now!') ?></span></h1>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col col-7 mx-auto text-center">
				<p><?= Yii::t('app', 'Select what to do') ?>:</p>
			</div>
		</div>

		<div class="row mt-4">
      <div class="col col-10 mx-auto text-left">
				<div class="row">
					<div class="col col-6 mx-auto text-left">
						<?= HTML::a( Yii::t('app', 'View available signals'), ['/signal/index'], ['class'=>'btn btn-secondary w-100']) ?><br>
						<?= HTML::a( Yii::t('app', 'View your subscriptions and payments'), ['/membership/index'], ['class'=>'btn btn-secondary w-100 mt-3']) ?><br>
						<?= HTML::a( Yii::t('app', 'View or update your profile'), ['/user/index'], ['class'=>'btn btn-secondary w-100 mt-3']) ?>
					</div>
					<div class="col col-6 mx-auto text-left">
						<?= HTML::a( Yii::t('app', 'Subscribe to a membersip'), ['/membership/subscribe'], ['class'=>'btn btn-secondary w-100']) ?><br>
						<?= HTML::a( Yii::t('app', 'View or add your 3Commas bot'), ['/bot'], ['class'=>'btn btn-secondary w-100 mt-3']) ?><br>
						<?= HTML::a( Yii::t('app', 'View or connect a signal to your bot'), ['/signal'], ['class'=>'btn btn-secondary w-100 mt-3']) ?>
					</div>
				</div>
			</div
		</div>

	</div>
</div>

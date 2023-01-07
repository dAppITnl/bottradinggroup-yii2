<?php

use Yii;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="updatemaxsignals-email">
    <p><?= Yii::t('app', 'Hello {username}', ['username' => Html::encode($row['username'])]) ?>,</p>

		<p><?= Yii::t('app', 'Your Bot Trading Group subscription to {membership} has ended at {enddate}.', ['membership'=>$row['membership', 'enddate'=>$row['enddate']] );</p>
    <p><?= Yii::t('app', 'You have to reactivate your bots, since the max signal count is now less then your number of bots.');</p>
    <p><?= Yii::t('app', 'Best regerds,');</p>
    <p><?= Yii::t('app', 'Team BTG');</p>
    <p><?= Yii::t('app', '');</p>

</div>

<?php

use Yii;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p><?= Yii::t('app', 'Hello {username}', ['username' => Html::encode($user->username)]) ?>,</p>

    <p><?= Yii::t('app', 'Follow the link below to verify your email') ?>:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>

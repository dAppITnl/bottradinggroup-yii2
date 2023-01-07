<?php

use Yii;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<?= Yii::t('common', 'Hello {username}', ['username' => $user->username]) ?>,

<?= Yii::t('common', 'Follow the link below to verify your email') ?>:

<?= $verifyLink ?>

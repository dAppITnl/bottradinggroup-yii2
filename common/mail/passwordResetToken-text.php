<?php

use Yii;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<?= Yii::t('common', 'Hello {username}', ['username' => $user->username]) ?>,

<?= Yii::t('common', 'Follow the link below to reset your password') ?>:

<?= $resetLink ?>

<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Check 2FA code';
?>
<div class="site-check2fa">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <p><?= Yii::t('app', 'Please enter the 2FA code from your authenticator') ?>:</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

						<?= $form->field($model, 'check2fa')->textInput([]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Check 2FA'), ['class' => 'btn btn-primary btn-block', 'name' => 'check2fa-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

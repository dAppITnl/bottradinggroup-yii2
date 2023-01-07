<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\UserSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'usr_language') ?>

		<?= $form->field($model, 'status') ?>

		<?= $form->field($model, 'username') ?>

		<?= $form->field($model, 'usr_password') ?>

		<?php // echo $form->field($model, 'usr_2fasecret') ?>

		<?php // echo $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'usr_firstname') ?>

		<?php // echo $form->field($model, 'usr_lastname') ?>

		<?php // echo $form->field($model, 'usr_countrycode') ?>

		<?php // echo $form->field($model, 'usr_sitelevel') ?>

		<?php // echo $form->field($model, 'usr_sitecsstheme') ?>

		<?php // echo $form->field($model, 'usr_signalactive') ?>

		<?php // echo $form->field($model, 'usr_moralisid') ?>

		<?php // echo $form->field($model, 'usr_discordusername') ?>

		<?php // echo $form->field($model, 'usr_discordnick') ?>

		<?php // echo $form->field($model, 'usr_discordjoinedat') ?>

		<?php // echo $form->field($model, 'usr_discordid') ?>

		<?php // echo $form->field($model, 'usr_discordroles') ?>

		<?php // echo $form->field($model, 'password_hash') ?>

		<?php // echo $form->field($model, 'password_reset_token') ?>

		<?php // echo $form->field($model, 'auth_key') ?>

		<?php // echo $form->field($model, 'verification_token') ?>

		<?php // echo $form->field($model, 'usr_remarks') ?>

		<?php // echo $form->field($model, 'usr_lock') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'usr_createdt') ?>

		<?php // echo $form->field($model, 'created_by') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

		<?php // echo $form->field($model, 'usr_updatedt') ?>

		<?php // echo $form->field($model, 'updated_by') ?>

		<?php // echo $form->field($model, 'deleted_at') ?>

		<?php // echo $form->field($model, 'usr_deletedt') ?>

		<?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

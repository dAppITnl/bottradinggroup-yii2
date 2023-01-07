<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\MembershipSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="membership-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'mbrcat_id') ?>

		<?= $form->field($model, 'mbr_language') ?>

		<?= $form->field($model, 'mbr_active') ?>

		<?= $form->field($model, 'mbr_active4admin') ?>

		<?php // echo $form->field($model, 'mbr_order') ?>

		<?php // echo $form->field($model, 'mbr_code') ?>

		<?php // echo $form->field($model, 'mbr_title') ?>

		<?php // echo $form->field($model, 'mbr_groupnr') ?>

		<?php // echo $form->field($model, 'mbr_cardbody') ?>

		<?php // echo $form->field($model, 'mbr_detailbody') ?>

		<?php // echo $form->field($model, 'mbr_discordroles') ?>

		<?php // echo $form->field($model, 'mbr_discordlogchanid') ?>

		<?php // echo $form->field($model, 'mbr_remarks') ?>

		<?php // echo $form->field($model, 'mbr_lock') ?>

		<?php // echo $form->field($model, 'mbr_createdat') ?>

		<?php // echo $form->field($model, 'mbr_createdt') ?>

		<?php // echo $form->field($model, 'mbrusr_created_id') ?>

		<?php // echo $form->field($model, 'mbr_updatedat') ?>

		<?php // echo $form->field($model, 'mbr_updatedt') ?>

		<?php // echo $form->field($model, 'mbrusr_updated_id') ?>

		<?php // echo $form->field($model, 'mbr_deletedat') ?>

		<?php // echo $form->field($model, 'mbr_deletedt') ?>

		<?php // echo $form->field($model, 'mbrusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\SignalSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="signal-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'sigcat_id') ?>

		<?= $form->field($model, 'sig_active') ?>

		<?= $form->field($model, 'sig_code') ?>

		<?= $form->field($model, 'sig_name') ?>

		<?php // echo $form->field($model, 'sig_3cactiontext') ?>

		<?php // echo $form->field($model, 'sig_description') ?>

		<?php // echo $form->field($model, 'sig_remarks') ?>

		<?php // echo $form->field($model, 'sig_lock') ?>

		<?php // echo $form->field($model, 'sig_createdt') ?>

		<?php // echo $form->field($model, 'sig_createdat') ?>

		<?php // echo $form->field($model, 'sigusr_created_id') ?>

		<?php // echo $form->field($model, 'sig_updatedat') ?>

		<?php // echo $form->field($model, 'sig_updatedt') ?>

		<?php // echo $form->field($model, 'sigusr_updated_id') ?>

		<?php // echo $form->field($model, 'sig_deletedat') ?>

		<?php // echo $form->field($model, 'sig_deletedt') ?>

		<?php // echo $form->field($model, 'sigusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

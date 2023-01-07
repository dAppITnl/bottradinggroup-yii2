<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\BotsignalSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="botsignal-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'bsg_lock') ?>

		<?= $form->field($model, 'bsgubt_id') ?>

		<?= $form->field($model, 'bsgsig_id') ?>

		<?= $form->field($model, 'bsgsym_id') ?>

		<?php // echo $form->field($model, 'bsg_active') ?>

		<?php // echo $form->field($model, 'bsg_name') ?>

		<?php // echo $form->field($model, 'bsg_startdate') ?>

		<?php // echo $form->field($model, 'bsg_enddate') ?>

		<?php // echo $form->field($model, 'bsg_remarks') ?>

		<?php // echo $form->field($model, 'bsg_createdat') ?>

		<?php // echo $form->field($model, 'bsg_createdt') ?>

		<?php // echo $form->field($model, 'bsgusr_created_id') ?>

		<?php // echo $form->field($model, 'bsg_updatedat') ?>

		<?php // echo $form->field($model, 'bsg_updatedt') ?>

		<?php // echo $form->field($model, 'bsgusr_updated_id') ?>

		<?php // echo $form->field($model, 'bsg_deletedat') ?>

		<?php // echo $form->field($model, 'bsg_deletedt') ?>

		<?php // echo $form->field($model, 'bsgusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

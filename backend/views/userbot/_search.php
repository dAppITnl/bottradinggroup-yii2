<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\UserbotSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="userbot-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'ubtumb_id') ?>

		<?= $form->field($model, 'ubtcat_id') ?>

		<?= $form->field($model, 'ubt_active') ?>

		<?= $form->field($model, 'ubt_signalstartstop') ?>

		<?php // echo $form->field($model, 'ubt_userstartstop') ?>

		<?php // echo $form->field($model, 'ubt_name') ?>

		<?php // echo $form->field($model, 'ubt_3cbotid') ?>

		<?php // echo $form->field($model, 'ubt_3cdealstartjson') ?>

		<?php // echo $form->field($model, 'ubt_remarks') ?>

		<?php // echo $form->field($model, 'ubt_lock') ?>

		<?php // echo $form->field($model, 'ubt_createdat') ?>

		<?php // echo $form->field($model, 'ubt_createdt') ?>

		<?php // echo $form->field($model, 'ubtusr_created_id') ?>

		<?php // echo $form->field($model, 'ubt_updatedat') ?>

		<?php // echo $form->field($model, 'ubt_updatedt') ?>

		<?php // echo $form->field($model, 'ubtusr_updated_id') ?>

		<?php // echo $form->field($model, 'ubt_deletedat') ?>

		<?php // echo $form->field($model, 'ubt_deletedt') ?>

		<?php // echo $form->field($model, 'ubtusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

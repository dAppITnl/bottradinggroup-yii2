<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\UsermemberSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="usermember-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'umbusr_id') ?>

		<?= $form->field($model, 'umbmbr_id') ?>

		<?= '' /*$form->field($model, 'umbprl_id')*/ ?>

		<?= '' /*$form->field($model, 'umbupy_id')*/ ?>

		<?php // echo $form->field($model, 'umb_active') ?>

		<?php  echo $form->field($model, 'umb_name') ?>

		<?php // echo $form->field($model, 'umb_roles') ?>

		<?php // echo $form->field($model, 'umb_startdate') ?>

		<?php // echo $form->field($model, 'umb_enddate') ?>

		<?php // echo $form->field($model, 'umb_paystartdate') ?>

		<?php // echo $form->field($model, 'umb_payenddate') ?>

		<?php // echo $form->field($model, 'umb_maxsignals') ?>

		<?php // echo $form->field($model, 'umb_remarks') ?>

		<?php // echo $form->field($model, 'umb_lock') ?>

		<?php // echo $form->field($model, 'umb_createdat') ?>

		<?php // echo $form->field($model, 'umb_createdt') ?>

		<?php // echo $form->field($model, 'umbusr_created_id') ?>

		<?php // echo $form->field($model, 'umb_updatedat') ?>

		<?php // echo $form->field($model, 'umb_updatedt') ?>

		<?php // echo $form->field($model, 'umbusr_updated_id') ?>

		<?php // echo $form->field($model, 'umb_deletedat') ?>

		<?php // echo $form->field($model, 'umb_deletedt') ?>

		<?php // echo $form->field($model, 'umbusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

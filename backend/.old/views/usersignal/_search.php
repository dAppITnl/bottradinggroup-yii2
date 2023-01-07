<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\UsersignalSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="usersignal-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'usg_lock') ?>

		<?= $form->field($model, 'usgusr_id') ?>

		<?= $form->field($model, 'usgbot_id') ?>

		<?= $form->field($model, 'usg_name') ?>

		<?php // echo $form->field($model, 'usg_emailtoken') ?>

		<?php // echo $form->field($model, 'usg_pair') ?>

		<?php // echo $form->field($model, 'usg_createdat') ?>

		<?php // echo $form->field($model, 'usgusr_created_id') ?>

		<?php // echo $form->field($model, 'usg_updatedat') ?>

		<?php // echo $form->field($model, 'usgusr_updated_id') ?>

		<?php // echo $form->field($model, 'usg_deletedat') ?>

		<?php // echo $form->field($model, 'usgusr_deleted_id') ?>

		<?php // echo $form->field($model, 'usg_createdt') ?>

		<?php // echo $form->field($model, 'usg_updatedt') ?>

		<?php // echo $form->field($model, 'usg_deletedt') ?>

		<?php // echo $form->field($model, 'usg_remarks') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

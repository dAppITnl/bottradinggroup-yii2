<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\CategorySearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'cat_lock') ?>

		<?= $form->field($model, 'cat_type') ?>

		<?= $form->field($model, 'cat_title') ?>

		<?= $form->field($model, 'cat_description') ?>

		<?php // echo $form->field($model, 'cat_createdat') ?>

		<?php // echo $form->field($model, 'catusr_created_id') ?>

		<?php // echo $form->field($model, 'cat_updatedat') ?>

		<?php // echo $form->field($model, 'catusr_updated_id') ?>

		<?php // echo $form->field($model, 'cat_deletedat') ?>

		<?php // echo $form->field($model, 'catusr_deleted_id') ?>

		<?php // echo $form->field($model, 'cat_createdt') ?>

		<?php // echo $form->field($model, 'cat_updatedt') ?>

		<?php // echo $form->field($model, 'cat_deletedt') ?>

		<?php // echo $form->field($model, 'cat_remarks') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

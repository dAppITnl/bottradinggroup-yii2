<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\SymbolSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="symbol-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'sym_type') ?>

		<?= $form->field($model, 'sym_isquote') ?>

		<?= $form->field($model, 'sym_ispair') ?>

		<?= $form->field($model, 'symsym_base_id') ?>

		<?php // echo $form->field($model, 'symsym_quote_id') ?>

		<?php // echo $form->field($model, 'symsym_network_id') ?>

		<?php // echo $form->field($model, 'sym_contractaddress') ?>

		<?php // echo $form->field($model, 'sym_code') ?>

		<?php // echo $form->field($model, 'sym_symbol') ?>

		<?php // echo $form->field($model, 'sym_name') ?>

		<?php // echo $form->field($model, 'sym_html') ?>

		<?php // echo $form->field($model, 'sym_description') ?>

		<?php // echo $form->field($model, 'sym_rateurl') ?>

		<?php // echo $form->field($model, 'sym_remarks') ?>

		<?php // echo $form->field($model, 'sym_lock') ?>

		<?php // echo $form->field($model, 'sym_createdat') ?>

		<?php // echo $form->field($model, 'sym_createdt') ?>

		<?php // echo $form->field($model, 'symusr_created_id') ?>

		<?php // echo $form->field($model, 'sym_updatedat') ?>

		<?php // echo $form->field($model, 'sym_updatedt') ?>

		<?php // echo $form->field($model, 'symusr_updated_id') ?>

		<?php // echo $form->field($model, 'sym_deletedat') ?>

		<?php // echo $form->field($model, 'sym_deletedt') ?>

		<?php // echo $form->field($model, 'symusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

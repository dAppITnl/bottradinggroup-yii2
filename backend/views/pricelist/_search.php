<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\PricelistSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="pricelist-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'prlmbr_id') ?>

		<?= $form->field($model, 'prlcat_id') ?>

		<?= $form->field($model, 'prlcad_crypto_ids') ?>

		<?= $form->field($model, 'prl_active') ?>

		<?php // echo $form->field($model, 'prl_active4admin') ?>

		<?php // echo $form->field($model, 'prl_name') ?>

		<?php // echo $form->field($model, 'prl_pretext') ?>

		<?php // echo $form->field($model, 'prl_posttext') ?>

		<?php // echo $form->field($model, 'prl_startdate') ?>

		<?php // echo $form->field($model, 'prl_enddate') ?>

		<?php // echo $form->field($model, 'prl_percode') ?>

		<?php // echo $form->field($model, 'prl_maxsignals') ?>

		<?php // echo $form->field($model, 'prl_allowedtimes') ?>

		<?php // echo $form->field($model, 'prl_discountcode') ?>

		<?php // echo $form->field($model, 'prlsym_id') ?>

		<?php // echo $form->field($model, 'prl_price') ?>

		<?php // echo $form->field($model, 'prl_remarks') ?>

		<?php // echo $form->field($model, 'prl_lock') ?>

		<?php // echo $form->field($model, 'prl_createdat') ?>

		<?php // echo $form->field($model, 'prl_createdt') ?>

		<?php // echo $form->field($model, 'prlusr_created_id') ?>

		<?php // echo $form->field($model, 'prl_updatedat') ?>

		<?php // echo $form->field($model, 'prl_updatedt') ?>

		<?php // echo $form->field($model, 'prlusr_updated_id') ?>

		<?php // echo $form->field($model, 'prl_deletedat') ?>

		<?php // echo $form->field($model, 'prl_deletedt') ?>

		<?php // echo $form->field($model, 'prlusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

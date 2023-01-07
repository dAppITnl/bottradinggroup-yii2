<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\CryptoaddressSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="cryptoaddress-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'cadsym_id') ?>

		<?= $form->field($model, 'cadusr_owner_id') ?>

		<?= $form->field($model, 'cad_type') ?>

		<?= $form->field($model, 'cadmbr_ids') ?>

		<?php // echo $form->field($model, 'cad_active') ?>

		<?php // echo $form->field($model, 'cad_payprovider') ?>

		<?php // echo $form->field($model, 'cad_ismainnet') ?>

		<?php // echo $form->field($model, 'cad_networkname') ?>

		<?php // echo $form->field($model, 'cad_networksettings') ?>

		<?php // echo $form->field($model, 'cad_tokenmetadata') ?>

		<?php // echo $form->field($model, 'cad_code') ?>

		<?php // echo $form->field($model, 'cad_name') ?>

		<?php // echo $form->field($model, 'cad_address') ?>

		<?php // echo $form->field($model, 'cad_memo') ?>

		<?php // echo $form->field($model, 'cad_decimals') ?>

		<?php // echo $form->field($model, 'cad_contract') ?>

		<?php // echo $form->field($model, 'cad_description') ?>

		<?php // echo $form->field($model, 'cad_remarks') ?>

		<?php // echo $form->field($model, 'cad_lock') ?>

		<?php // echo $form->field($model, 'cad_createdat') ?>

		<?php // echo $form->field($model, 'cad_createdt') ?>

		<?php // echo $form->field($model, 'cadusr_created_id') ?>

		<?php // echo $form->field($model, 'cad_updatedat') ?>

		<?php // echo $form->field($model, 'cad_updatedt') ?>

		<?php // echo $form->field($model, 'cadusr_updated_id') ?>

		<?php // echo $form->field($model, 'cad_deletedat') ?>

		<?php // echo $form->field($model, 'cad_deletedt') ?>

		<?php // echo $form->field($model, 'cadusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

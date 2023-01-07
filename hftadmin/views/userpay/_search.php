<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var hftadmin\models\UserpaySearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="userpay-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'upyusr_id') ?>

		<?= $form->field($model, 'upyumb_id') ?>

		<?= $form->field($model, 'upymbr_id') ?>

		<?= $form->field($model, 'upycat_id') ?>

		<?php // echo $form->field($model, 'upyprl_id') ?>

		<?php // echo $form->field($model, 'upy_state') ?>

		<?php // echo $form->field($model, 'upy_name') ?>

		<?php // echo $form->field($model, 'upy_maxsignals') ?>

		<?php // echo $form->field($model, 'upy_percode') ?>

		<?php // echo $form->field($model, 'upy_startdate') ?>

		<?php // echo $form->field($model, 'upy_enddate') ?>

		<?php // echo $form->field($model, 'upy_discountcode') ?>

		<?php // echo $form->field($model, 'upy_payamount') ?>

		<?php // echo $form->field($model, 'upy_cryptoamount') ?>

		<?php // echo $form->field($model, 'upysym_pay_id') ?>

		<?php // echo $form->field($model, 'upysym_crypto_id') ?>

		<?php // echo $form->field($model, 'upy_rate') ?>

		<?php // echo $form->field($model, 'upysym_rate_id') ?>

		<?php // echo $form->field($model, 'upy_payprovider') ?>

		<?php // echo $form->field($model, 'upy_providermode') ?>

		<?php // echo $form->field($model, 'upy_providername') ?>

		<?php // echo $form->field($model, 'upycad_to_id') ?>

		<?php // echo $form->field($model, 'upy_toaccount') ?>

		<?php // echo $form->field($model, 'upy_payref') ?>

		<?php // echo $form->field($model, 'upy_providerid') ?>

		<?php // echo $form->field($model, 'upy_payreply') ?>

		<?php // echo $form->field($model, 'upy_fromaccount') ?>

		<?php // echo $form->field($model, 'upy_paiddt') ?>

		<?php // echo $form->field($model, 'upy_remarks') ?>

		<?php // echo $form->field($model, 'upy_lock') ?>

		<?php // echo $form->field($model, 'upy_createdat') ?>

		<?php // echo $form->field($model, 'upy_createdt') ?>

		<?php // echo $form->field($model, 'upyusr_created_id') ?>

		<?php // echo $form->field($model, 'upy_updatedat') ?>

		<?php // echo $form->field($model, 'upy_updatedt') ?>

		<?php // echo $form->field($model, 'upyusr_updated_id') ?>

		<?php // echo $form->field($model, 'upy_deletedat') ?>

		<?php // echo $form->field($model, 'upy_deletedt') ?>

		<?php // echo $form->field($model, 'upyusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

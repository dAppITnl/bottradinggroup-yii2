<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\SignallogSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="signallog-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'slgbsg_id') ?>

		<?= $form->field($model, 'slgsig_id') ?>

		<?= $form->field($model, 'slg_status') ?>

		<?= $form->field($model, 'slg_alertmsg') ?>

		<?php // echo $form->field($model, 'slg_senddata') ?>

		<?php // echo $form->field($model, 'slg_message') ?>

		<?php // echo $form->field($model, 'slg_discordlogchanid') ?>

		<?php // echo $form->field($model, 'slg_discordlogmessage') ?>

		<?php // echo $form->field($model, 'slg_discordtologat') ?>

		<?php // echo $form->field($model, 'slg_discordlogdone') ?>

		<?php // echo $form->field($model, 'slg_discordlogdelaydone') ?>

		<?php // echo $form->field($model, 'slg_remarks') ?>

		<?php // echo $form->field($model, 'slg_lock') ?>

		<?php // echo $form->field($model, 'slg_createdat') ?>

		<?php // echo $form->field($model, 'slg_createdt') ?>

		<?php // echo $form->field($model, 'slgusr_created_id') ?>

		<?php // echo $form->field($model, 'slg_updatedat') ?>

		<?php // echo $form->field($model, 'slg_updatedt') ?>

		<?php // echo $form->field($model, 'slgusr_updated_id') ?>

		<?php // echo $form->field($model, 'slg_deletedat') ?>

		<?php // echo $form->field($model, 'slg_deletedt') ?>

		<?php // echo $form->field($model, 'slgusr_deleted_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

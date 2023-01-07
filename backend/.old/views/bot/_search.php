<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var backend\models\BotSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="bot-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'botcat_id') ?>

		<?= $form->field($model, 'bot_lock') ?>

		<?= $form->field($model, 'bot_name') ?>

		<?= $form->field($model, 'bot_3cbotid') ?>

		<?php // echo $form->field($model, 'bot_dealstartjson') ?>

		<?php // echo $form->field($model, 'bot_costmonth') ?>

		<?php // echo $form->field($model, 'botsym_cost_id') ?>

		<?php // echo $form->field($model, 'bot_createdat') ?>

		<?php // echo $form->field($model, 'botusr_created_id') ?>

		<?php // echo $form->field($model, 'bot_updatedat') ?>

		<?php // echo $form->field($model, 'botusr_updated_id') ?>

		<?php // echo $form->field($model, 'bot_deletedat') ?>

		<?php // echo $form->field($model, 'botusr_deleted_id') ?>

		<?php // echo $form->field($model, 'bot_createdt') ?>

		<?php // echo $form->field($model, 'bot_updatedt') ?>

		<?php // echo $form->field($model, 'bot_deletedt') ?>

		<?php // echo $form->field($model, 'bot_remarks') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('cruds', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('cruds', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

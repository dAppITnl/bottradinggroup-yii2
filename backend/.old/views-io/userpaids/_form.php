<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Userpaids */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="userpaids-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'usrpay_name')->textInput(['maxlength' => true, 'placeholder' => 'Usrpay Name']) ?>

    <?= $form->field($model, 'usrpay_userId')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_startdt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Startdt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_enddt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Enddt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_payamount')->textInput(['maxlength' => true, 'placeholder' => 'Usrpay Payamount']) ?>

    <?= $form->field($model, 'usrpay_paysymbol')->textInput(['maxlength' => true, 'placeholder' => 'Usrpay Paysymbol']) ?>

    <?= $form->field($model, 'usrpay_rate')->textInput(['maxlength' => true, 'placeholder' => 'Usrpay Rate']) ?>

    <?= $form->field($model, 'usrpay_ratesymbol')->textInput(['maxlength' => true, 'placeholder' => 'Usrpay Ratesymbol']) ?>

    <?= $form->field($model, 'usrpay_paiddt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Paiddt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_createdt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Createdt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_updatedt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Updatedt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_deletedt')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Usrpay Deletedt'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'usrpay_remarks')->textarea(['rows' => 6]) ?>

    <div class="form-group">
    <?php if(Yii::$app->controller->action->id != 'save-as-new'): ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php endif; ?>
    <?php if(Yii::$app->controller->action->id != 'create'): ?>
        <?= Html::submitButton(Yii::t('app', 'Save As New'), ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
    <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

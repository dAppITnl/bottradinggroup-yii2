<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use \common\helpers\GeneralHelper;

$actionIsUpdate = !empty($userbotModel->id);
$tierName = Html::encode($usermemberModel->umbmbr->mbr_title);

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="usermember-newbot">
  <h1><?= ($actionIsUpdate
					? Yii::t('app', "Update your 3Commas bot for your '{tierName}'.", ['tierName' => $tierName])
					: Yii::t('app', "Add your 3Commas bot for your '{tierName}'.", ['tierName' => $tierName])) ?></h1>

  <h3><?= Yii::t('app', 'Your membership:'); ?></h3>
  <?= DetailView::widget([
    'model' => $usermemberModel,
    'attributes' => [
      [
        'format' => 'raw',
        'attribute' => '',
        'value' => $getPricelistPeriods[ $usermemberModel->umbprl->prl_percode ],
      ],
      'umb_startdate',
      'umb_enddate',
      [
        'format' => 'raw',
        'attribute' => 'umbprl_id',
        'value' => $usermemberModel->umbprl->prlsym->sym_code . ' ' . $usermemberModel->umbprl->prl_price, //. ' (' . $usermemberModel->umbprl->prl_name . ')',
      ],
    ],
  ]); ?>

  <hr>

  <div class="userbot-new">

    <?php $form = ActiveForm::begin([
      'id' => 'Userbot',
      'layout' => 'horizontal',
      'enableClientValidation' => true,
      'errorSummaryCssClass' => 'error-summary alert alert-danger',
      'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
          'label' => 'col-sm-2',
          #'offset' => 'col-sm-offset-4',
          'wrapper' => 'col-sm-8',
          'error' => '',
          'hint' => '',
        ],
      ],
    ]); ?>

    <div class="">
      <p>
      <?= $form->field($userbotModel, 'ubt_name')->textInput(['maxlength' => true, ]) ?>

			<?= $form->field($userbotModel, 'ubt_active')->checkbox([/*'label' => false*/]); ?>

      <?= $form->field($userbotModel, 'ubt_3cdealstartjson')->textarea(['rows' => 6, ]) ?>

      <?= $form->field($userbotModel, 'ubt_remarks')->textarea(['rows' => 3, ]) ?>
      </p>

      <hr/>

      <?php echo $form->errorSummary($userbotModel); ?>

      <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . ($actionIsUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Add')),
        ['id' => 'save-' . $userbotModel->formName(),  'class' => 'btn btn-success']
      ); ?>

      <?= Html::a(Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>


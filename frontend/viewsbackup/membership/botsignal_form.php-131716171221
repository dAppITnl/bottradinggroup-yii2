<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use frontend\models\Signal;
use \common\helpers\GeneralHelper;

$actionIsUpdate = !empty($botsignalModel->id);
$botName = Html::encode($userbotModel->ubt_name);

$signals = GeneralHelper::getCategorySignalsForUserBotsignal(
	Yii::$app->user->identity->usr_language,
	$userbotModel->ubtumb->umbmbr_id,
	'',
	''); //Signal::getSignalsForUserBotsignal();
Yii::trace('** botsignal_form signals: '.print_r($signals, true));

/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="usermember-botsignal">
  <h1><?= ($actionIsUpdate
					? Yii::t('app', "Update your signal for your 3Commas bot '{botName}'.", ['botName' => $botName])
					: Yii::t('app', "Add a signal to your 3Commas bot '{botName}'.", ['botName' => $botName])) ?></h1>

  <h3><?= Yii::t('app', 'Your bot:'); ?></h3>
  <?= DetailView::widget([
    'model' => $userbotModel,
    'attributes' => [
      [
        'format' => 'raw',
        'attribute' => 'umb_name',
        'value' => $userbotModel->ubtumb->umb_name,  //$getPricelistPeriods[ $usermemberModel->umbprl->prl_percode ],
      ],
      'ubt_name',
      'ubt_3cbotid',
			'ubt_remarks:ntext',
      /*[
        'format' => 'raw',
        'attribute' => 'umbprl_id',
        'value' => $usermemberModel->umbprl->prlsym->sym_code . ' ' . $usermemberModel->umbprl->prl_price, //. ' (' . $usermemberModel->umbprl->prl_name . ')',
      ],*/
    ],
  ]); ?>

  <hr>

  <div class="botsignal-form">

    <?php $form = ActiveForm::begin([
      'id' => 'Botsignal',
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
			<?= $form->field($botsignalModel, 'bsg_active')->checkbox([ ]) ?>

      <?= $form->field($botsignalModel, 'bsgsig_id')->dropDownList($signals,
        // ['multiple'=>'multiple', 'class'=>'chosen-select input-md required', ]); /*->label("Add Categories");*/
        // \yii\helpers\ArrayHelper::map(backend\models\Signal::find()->all(), 'id', 'sig_name'),
        [
          'prompt' => Yii::t('cruds', 'Select'),
          'disabled' => (isset($relAttributes) && isset($relAttributes['bsgsig_id'])),
        ]
      );
 ?>

      </p>

      <hr/>

      <?php echo $form->errorSummary($botsignalModel); ?>

      <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . ($actionIsUpdate ? Yii::t('app', 'Update') : Yii::t('app', 'Add')),
        ['id' => 'save-' . $botsignalModel->formName(),  'class' => 'btn btn-success']
      ); ?>

      <?= Html::a(Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>


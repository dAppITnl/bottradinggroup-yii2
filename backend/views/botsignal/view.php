<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use backend\models\Signal;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);

//$signals = Signal::getSignalsForUserBotsignal();

/**
* @var yii\web\View $this
* @var backend\models\Botsignal $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Botsignal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Botsignal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud botsignal-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Botsignal') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Botsignal'=>$copyParams],
        ['class' => 'btn btn-success'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
        ['create'],
        ['class' => 'btn btn-success'])
      ?>
    </div>

    <div class="pull-right">
       <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
         . Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
    </div>

  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\Botsignal'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      //'bsg_name',
      [
        'format' => 'html',
        'attribute' => 'bsg_active',
        'value' => $yesNos[$model->bsg_active],
      ],
			[
    		'format' => 'html',
    		'attribute' => 'bsgubt_id',
    		'value' => ($model->bsgubt ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['userbot/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->bsgubt->ubt_name, ['userbot/view', 'id' => $model->bsgubt->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Botsignal'=>['bsgubt_id' => $model->bsgubt_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      /*[
        'format' => 'html',
        'attribute' => 'bsgsig_ids',
        'value' => function($model) use ($signals) {
          $result = [];
          if (!empty($model->bsgsig_ids)) foreach(explode(',', $model->bsgsig_ids) as $sigid) {
            $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$signals[$sigid], ['signal/view', 'id'=>$sigid]);
          }
          return implode(",<br>\n", $result);
        },
      ],*/
			[
    		'format' => 'html',
    		'attribute' => 'bsgsig_id',
    		'value' => ($model->bsgsig ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['signal/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->bsgsig->sig_name, ['signal/view', 'id' => $model->bsgsig->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Botsignal'=>['bsgsig_id' => $model->bsgsig_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
     'bsg_remarks:ntext',

      //'bsg_lock',
      //'bsg_createdat',
      'bsg_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'bsgusr_created_id',
    		'value' => ($model->bsgusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->bsgusrCreated->username, ['user/view', 'id' => $model->bsgusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Botsignal'=>['bsgusr_created_id' => $model->bsgusr_created_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      //'bsg_updatedat',
      'bsg_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'bsgusr_updated_id',
    		'value' => ($model->bsgusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->bsgusrUpdated->username, ['user/view', 'id' => $model->bsgusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Botsignal'=>['bsgusr_updated_id' => $model->bsgusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      //'bsg_deletedat',
      'bsg_deletedt',
      [
        'format' => 'html',
        'attribute' => 'bsgusr_deleted_id',
        'value' => ($model->bsgusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->bsgusrDeleted->username, ['user/view', 'id' => $model->bsgusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Botsignal'=>['bsgusr_deleted_id' => $model->bsgusr_deleted_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
    ],
  ]); ?>

  <hr/>

  <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('cruds', 'Delete'), ['delete', 'id' => $model->id],
    [
    	'class' => 'btn btn-danger',
    	'data-confirm' => '' . Yii::t('cruds', 'Are you sure to delete this item?') . '',
    	'data-method' => 'post',
    ]
	); ?>
  <?php $this->endBlock(); ?>

  <?= Tabs::widget(
    [
      'id' => 'relation-tabs',
      'encodeLabels' => false,
      'items' => [
 				[
    			'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    			'content' => $this->blocks['backend\models\Botsignal'],
    			'active'  => true,
				],
			]
    ]
  ); ?>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
//use common\helpers\GeneralHelper;

//$signallogStates = GeneralHelper::getSignallogStates();

/**
* @var yii\web\View $this
* @var backend\models\Signallog $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Signallog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Signallog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud signallog-view">
	<!-- flash message -->
  <?php
    if (\Yii::$app->session->getFlash('deleteError') !== null) :
  ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Signallog') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= '' /* Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Signallog'=>$copyParams],
        ['class' => 'btn btn-success']) */
      ?>

      <?= '' /*Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
        ['create'],
        ['class' => 'btn btn-success']) */
      ?>
    </div>

    <div class="pull-right">
      <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
        . Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default'])
			?>
    </div>
  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\Signallog'); ?>
  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
			[
		    'format' => 'html',
		    'attribute' => 'slgsig_id',
		    'value' => ($model->slgsig ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['signal/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->slgsig->sig_name, ['signal/view', 'id' => $model->slgsig->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signallog'=>['slgsig_id' => $model->slgsig_id]])
		    :
		      '<span class="label label-warning">?</span>'),
		  ],
 			[
		    'format' => 'html',
		    'attribute' => 'slgbsg_id',
		    'value' => ($model->slgbsg ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['botsignal/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->slgbsg->id /*bsgubt->ubt_name*/, ['botsignal/view', 'id' => $model->slgbsg->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signallog'=>['slgbsg_id' => $model->slgbsg_id]])
		    :
		      '<span class="label label-warning">?</span>'),
			],
			'slg_status',
		  /*[
		    'attribute'=>'slg_status',
		    'value' => $signallogStates[$model->slg_status], //backend\models\Signallog::getSlgStatusValueLabel($model->slg_status),
		  ],*/
			'slg_alertmsg',
			[
				'format' => 'raw',
				'attribute' => 'slg_senddata',
				'contentOptions' => ['style'=>'white-space:normal'],
				'value' => function ($model) {
					return '<pre style="line-height:1em; height:16em; max-width:99%; overflow:auto;">'
						.\yii\helpers\HtmlPurifier::process(json_encode(json_decode($model->slg_senddata), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						.'</pre>';
					},
			],

			'slg_discordlogmessage:ntext',
			'slg_discordlogchanid',
			'slg_discordtologat',
			'slg_discordlogdone:ntext',
			'slg_discordlogdelaydone:ntext',

		  'slg_message:ntext',
		  'slg_remarks:ntext',

      //'slg_lock',

      //'slg_createdat',
      'slg_createdt',
			[
		    'format' => 'html',
		    'attribute' => 'slgusr_created_id',
		    'value' => ($model->slgusrCreated ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->slgusrCreated->username, ['user/view', 'id' => $model->slgusrCreated->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signallog'=>['slgusr_created_id' => $model->slgusr_created_id]])
		    :
		      '<span class="label label-warning">?</span>'),
			],

      //'slg_updatedat',
      'slg_updatedt',
			[
		    'format' => 'html',
		    'attribute' => 'slgusr_updated_id',
		    'value' => ($model->slgusrUpdated ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->slgusrUpdated->username, ['user/view', 'id' => $model->slgusrUpdated->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signallog'=>['slgusr_updated_id' => $model->slgusr_updated_id]])
		    :
		      '<span class="label label-warning">?</span>'),
			],

      //'slg_deletedat',
      'slg_deletedt',
      [
        'format' => 'html',
        'attribute' => 'slgusr_deleted_id',
        'value' => ($model->slgusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->slgusrDeleted->username, ['user/view', 'id' => $model->slgusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signallog'=>['slgusr_deleted_id' => $model->slgusr_deleted_id]])
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

  <?= Tabs::widget([
    'id' => 'relation-tabs',
    'encodeLabels' => false,
    'items' => [
			[
   			'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
  			'content' => $this->blocks['backend\models\Signallog'],
   			'active'  => true,
			],
 		]
  ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var backend\models\Usersignal $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Usersignal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Usersignal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud usersignal-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Usersignal') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Usersignal'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Usersignal'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'usg_name',
			[
    		'format' => 'html',
    		'attribute' => 'usgusr_id',
    		'value' => ($model->usgusr ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->usgusr->username, ['user/view', 'id' => $model->usgusr->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usersignal'=>['usgusr_id' => $model->usgusr_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'usgbot_id',
    		'value' => ($model->usgbot ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['bot/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->usgbot->bot_name, ['bot/view', 'id' => $model->usgbot->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usersignal'=>['usgbot_id' => $model->usgbot_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      'usg_emailtoken:email',
      'usg_pair',
      'usg_remarks:ntext',

      //'usg_lock',
      //'usg_createdat',
      'usg_createdt',
      [
        'format' => 'html',
        'attribute' => 'usgusr_created_id',
        'value' => ($model->usgusrCreated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->usgusrCreated->username, ['user/view', 'id' => $model->usgusrCreated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usersignal'=>['usgusr_created_id' => $model->usgusr_created_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      //'usg_updatedat',
      'usg_updatedt',
      [
        'format' => 'html',
        'attribute' => 'usgusr_updated_id',
        'value' => ($model->usgusrUpdated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->usgusrUpdated->username, ['user/view', 'id' => $model->usgusrUpdated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usersignal'=>['usgusr_updated_id' => $model->usgusr_updated_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      //'usg_deletedat',
      'usg_deletedt',
			[
    		'format' => 'html',
    		'attribute' => 'usgusr_deleted_id',
    		'value' => ($model->usgusrDeleted ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->usgusrDeleted->username, ['user/view', 'id' => $model->usgusrDeleted->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usersignal'=>['usgusr_deleted_id' => $model->usgusr_deleted_id]])
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
    		'content' => $this->blocks['backend\models\Usersignal'],
    		'active'  => true,
			],
 		]
  ]); ?>
</div>

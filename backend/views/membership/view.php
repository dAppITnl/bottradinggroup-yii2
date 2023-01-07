<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
//$membershipRoles = GeneralHelper::getMembershipRoles();
$languages = GeneralHelper::getLanguages();
$discordRoles = GeneralHelper::getDiscordRoles();

/** * @var yii\web\View $this * @var backend\models\Membership $model */ $copyParams = $model->attributes;

$this->title = Yii::t('models', 'Membership');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Membership'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud membership-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Membership') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Membership'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Membership'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'mbr_code',
      'mbr_title',
      [
        'format' => 'html',
        'attribute' => 'mbr_active',
        'value' => $yesNos[$model->mbr_active],
      ],
      [
        'format' => 'html',
        'attribute' => 'mbr_active4admin',
        'value' => $yesNos[$model->mbr_active4admin],
      ],
			'mbr_groupnr',
      'mbr_order',
      [
        'format' => 'html',
        'attribute' => 'mbr_language',
        'value' => $languages[$model->mbr_language],
      ],
			[
    		'format' => 'html',
    		'attribute' => 'mbrcat_id',
    		'value' => ($model->mbrcat ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->mbrcat->cat_title, ['category/view', 'id' => $model->mbrcat->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Membership'=>['mbrcat_id' => $model->mbrcat_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      'mbr_cardbody:ntext',
      'mbr_detailbody:ntext',
      [
        'attribute'=>'mbr_discordroles',
        'value' => function($model) use ($discordRoles) {
          $result = [];
          foreach(explode(',', $model->mbr_discordroles) as $role) $result[] = $discordRoles[ $role ];
          return implode(', ',$result);
        }
      ],
			'mbr_discordlogchanid',

      'mbr_remarks:ntext',

      //'mbr_lock',
      'mbr_createdt',
      //'mbr_createdat',
      [
        'format' => 'html',
        'attribute' => 'mbrusr_created_id',
        'value' => ($model->mbrusrCreated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->mbrusrCreated->username, ['user/view', 'id' => $model->mbrusrCreated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Membership'=>['mbrusr_created_id' => $model->mbrusr_created_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      'mbr_updatedt',
      //'mbr_updatedat',
      [
        'format' => 'html',
        'attribute' => 'mbrusr_updated_id',
        'value' => ($model->mbrusrUpdated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->mbrusrUpdated->username, ['user/view', 'id' => $model->mbrusrUpdated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Membership'=>['mbrusr_updated_id' => $model->mbrusr_updated_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      'mbr_deletedt',
      //'mbr_deletedat',
      [
        'format' => 'html',
        'attribute' => 'mbrusr_deleted_id',
        'value' => ($model->mbrusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->mbrusrDeleted->username, ['user/view', 'id' => $model->mbrusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Membership'=>['mbrusr_deleted_id' => $model->mbrusr_deleted_id]])
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
    			'content' => $this->blocks['backend\models\Membership'],
    			'active'  => true,
				],
			]
    ]
	); ?>
</div>

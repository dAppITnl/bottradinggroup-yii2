<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
//$getMembershipRoles = GeneralHelper::getMembershipRoles();

/**
* @var yii\web\View $this
* @var backend\models\Usermember $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Usermember');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Usermember'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud usermember-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Usermember') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Usermember'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Usermember'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'umb_name',
      [
        'format' => 'html',
        'attribute' => 'umb_active',
        'value' => $yesNos[$model->umb_active],
      ],
      [
        'format' => 'html',
        'attribute' => 'umbusr_id',
        'value' => ($model->umbusr ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbusr->username, ['user/view', 'id' => $model->umbusr->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbusr_id' => $model->umbusr_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      [
        'format' => 'html',
        'attribute' => 'umbmbr_id',
        'value' => ($model->umbmbr ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['membership/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbmbr->mbr_title, ['membership/view', 'id' => $model->umbmbr->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbmbr_id' => $model->umbmbr_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      /*[
        'format' => 'raw',
        'attribute' => 'umb_roles',
        'value' => function($model) use ($getMembershipRoles) {
          $result = [];
          if (!empty($model->umb_roles)) {
            $roles = explode(',', $model->umb_roles);
            foreach($getMembershipRoles as $key => $value) {
              if (in_array($key, $roles)) $result[] = $value;
            }
          }
          return implode(', ', $result);
        },
      ],*/
			//'umb_maxsignals',
      //'umb_startdate:date',
      //'umb_enddate:date',
      /*[
        'format' => 'html',
        'attribute' => 'umbprl_id',
        'value' => ($model->umbprl ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['pricelist/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbprl->prl_name, ['pricelist/view', 'id' => $model->umbprl->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbprl_id' => $model->umbprl_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],*/
			/*[
		    'format' => 'html',
		    'attribute' => 'umbupy_id',
		    'value' => ($model->umbupy ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['userpay/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbupy->upy_name.': '.$model->umbupy->upy_payref, ['userpay/view', 'id' => $model->umbupy->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbupy_id' => $model->umbupy_id]])
		    :
		      '<span class="label label-warning">?</span>'),
		  ],*/
      'umb_remarks:ntext',

      //'umb_lock',

      'umb_createdt',
      //'umb_createdat',
			[
    		'format' => 'html',
    		'attribute' => 'umbusr_created_id',
    		'value' => ($model->umbusrCreated ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbusrCreated->username, ['user/view', 'id' => $model->umbusrCreated->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbusr_created_id' => $model->umbusr_created_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],

      'umb_updatedt',
      //'umb_updatedat',
			[
    		'format' => 'html',
    		'attribute' => 'umbusr_updated_id',
    		'value' => ($model->umbusrUpdated ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbusrUpdated->username, ['user/view', 'id' => $model->umbusrUpdated->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbusr_updated_id' => $model->umbusr_updated_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],

      'umb_deletedt',
      //'umb_deletedat',
			[
    		'format' => 'html',
    		'attribute' => 'umbusr_deleted_id',
    		'value' => ($model->umbusrDeleted ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->umbusrDeleted->username, ['user/view', 'id' => $model->umbusrDeleted->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Usermember'=>['umbusr_deleted_id' => $model->umbusr_deleted_id]])
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
    			'content' => $this->blocks['backend\models\Usermember'],
    			'active'  => true,
				],
 			]
    ]
  ); ?>
</div>

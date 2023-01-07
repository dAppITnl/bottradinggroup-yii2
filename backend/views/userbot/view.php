<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);


/**
* @var yii\web\View $this
* @var backend\models\Userbot $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Userbot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Userbot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud userbot-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Userbot') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Userbot'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Userbot'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'ubt_name',
      [
				'format' => 'html',
				'attribute' => 'ubt_active',
				'value' => $yesNos[ $model->ubt_active ],
			],
			[
        'format' => 'html',
        'attribute' => 'ubt_signalstartstop',
        'value' => $yesNos[ $model->ubt_signalstartstop ],
      ],
			[
        'format' => 'html',
        'attribute' => 'ubt_userstartstop',
        'value' => $yesNos[ $model->ubt_userstartstop ],
      ],

			[
    		'format' => 'html',
    		'attribute' => 'ubtumb_id',
    		'value' => ($model->ubtumb ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['usermember/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->ubtumb->umb_name, ['usermember/view', 'id' => $model->ubtumb->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userbot'=>['ubtumb_id' => $model->ubtumb_id]])
        :
	        '<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'ubtcat_id',
    		'value' => ($model->ubtcat ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->ubtcat->cat_type, ['category/view', 'id' => $model->ubtcat->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userbot'=>['ubtcat_id' => $model->ubtcat_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      'ubt_3cbotid',
      'ubt_3cdealstartjson:ntext',
      'ubt_remarks:ntext',

      //'ubt_lock',

      //'ubt_createdat',
      'ubt_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'ubtusr_created_id',
    		'value' => ($model->ubtusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->ubtusrCreated->username, ['user/view', 'id' => $model->ubtusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userbot'=>['ubtusr_created_id' => $model->ubtusr_created_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      //'ubt_updatedat',
      'ubt_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'ubtusr_updated_id',
    		'value' => ($model->ubtusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->ubtusrUpdated->username, ['user/view', 'id' => $model->ubtusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userbot'=>['ubtusr_updated_id' => $model->ubtusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      //'ubt_deletedat',
      'ubt_deletedt',
      [
        'format' => 'html',
        'attribute' => 'ubtusr_deleted_id',
        'value' => ($model->ubtusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->ubtusrDeleted->username, ['user/view', 'id' => $model->ubtusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userbot'=>['ubtusr_deleted_id' => $model->ubtusr_deleted_id]])
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


<?php $this->beginBlock('Botsignals'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals',
		           ['botsignal/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals',
		            ['botsignal/create', 'Botsignal' => ['bsgubt_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Botsignals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Botsignals ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getBotsignals(),
		       'pagination' => [
		           'pageSize' => 20,
		           'pageParam'=>'page-botsignals',
		       ]
		   ]),
		   'pager'       => [
		       'class'         => yii\widgets\LinkPager::className(),
		       'firstPageLabel' => Yii::t('cruds', 'First'),
		       'lastPageLabel' => Yii::t('cruds', 'Last')
		   ],
		   'columns' => [
		[
		   'class'     => 'yii\grid\ActionColumn',
		   'template'  => '{view} {update}',
		   'contentOptions' => ['nowrap'=>'nowrap'],
		   'urlCreator' => function ($action, $model, $key, $index) {
		       // using the column name as key, not mapping to 'id' like the standard generator
		       $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
		       $params[0] = 'botsignal' . '/' . $action;
		       $params['Botsignal'] = ['bsgubt_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'botsignal'
		],
		       'id',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'bsgsig_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgsig) {
		           return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'bsg_active',
		       'bsg_remarks:ntext',
		       'bsg_lock',
		       'bsg_createdat',
		       'bsg_createdt',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'bsgusr_created_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgusrCreated) {
		           return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'bsg_updatedat',
		]
		])
		. '</div>'
		?>
		<?php Pjax::end() ?>
		<?php $this->endBlock() ?>
		
		
		<?php $this->beginBlock('Bsgsigs'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Bsgsigs',
		           ['signal/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Bsgsigs',
		            ['signal/create'],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-link"></span> ' . Yii::t('cruds', 'Attach') . ' Bsgsig', ['botsignal/create', 'Botsignal'=>['bsgubt_id'=>$model->id]],
		           ['class'=>'btn btn-info btn-xs']
		       ) ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Bsgsigs', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Bsgsigs ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getBotsignals(),
		       'pagination' => [
		           'pageSize' => 20,
		           'pageParam'=>'page-botsignals',
		       ]
		   ]),
		   'pager'       => [
		       'class'         => yii\widgets\LinkPager::className(),
		       'firstPageLabel' => Yii::t('cruds', 'First'),
		       'lastPageLabel' => Yii::t('cruds', 'Last')
		   ],
		   'columns' => [
		[
		   'class'     => 'yii\grid\ActionColumn',
		   'template'  => '{view} {update}',
		   'contentOptions' => ['nowrap'=>'nowrap'],
		   'urlCreator' => function ($action, $model, $key, $index) {
		       // using the column name as key, not mapping to 'id' like the standard generator
		       $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
		       $params[0] = 'botsignal' . '/' . $action;
		       $params['Botsignal'] = ['bsgubt_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'botsignal'
		],
		       'id',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'bsgsig_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgsig) {
		           return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'bsg_active',
		       'bsg_remarks:ntext',
		       'bsg_lock',
		       'bsg_createdat',
		       'bsg_createdt',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'bsgusr_created_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgusrCreated) {
		           return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'bsg_updatedat',
		]
		])
		. '</div>'
		?>
		<?php Pjax::end() ?>
		<?php $this->endBlock() ?>

  <?= Tabs::widget(
    [
      'id' => 'relation-tabs',
      'encodeLabels' => false,
      'items' => [
				[
    			'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    			'content' => $this->blocks['backend\models\Userbot'],
    			'active'  => true,
				],
				[
		   		'content' => $this->blocks['Botsignals'],
		   		'label'  => '<small>Botsignals <span class="badge badge-default">'. $model->getBotsignals()->count() . '</span></small>',
		   		'active' => false,
				],
				[
		   		'content' => $this->blocks['Bsgsigs'],
		   		'label'  => '<small>Bsgsigs <span class="badge badge-default">'. $model->getBsgsigs()->count() . '</span></small>',
		   		'active' => false,
				],
 			]
    ]
  ); ?>
</div>

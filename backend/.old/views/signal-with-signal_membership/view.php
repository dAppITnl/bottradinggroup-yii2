<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
//use yii\i18n\Formatter;
use dmstr\bootstrap\Tabs;
use \backend\models\Signal;
use common\helpers\GeneralHelper;

$getYesNos = GeneralHelper::getYesNos();

$sig3CActiontexts = Signal::optsSig3cActionTexts();

/**
* @var yii\web\View $this
* @var backend\models\Signal $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Signal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Signal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud signal-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Signal') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Signal'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Signal'); ?>
  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'sig_name',
			'sig_description:raw',
      'sig_code',
			[
				'format' => 'html',
        'attribute' => 'sig_active',
        'value' => ''.$getYesNos[$model->sig_active],
			],
			[
    		'format' => 'html',
    		'attribute' => 'sigcat_id',
    		'value' => ($model->sigcat ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigcat->cat_title, ['category/view', 'id' => $model->sigcat->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigcat_id' => $model->sigcat_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      [
        'format' => 'html',
        'attribute' => 'sig_3cactiontext',
        'value' => ''.$sig3CActiontexts[$model->sig_3cactiontext],
      ],

			[
		    'format' => 'html',
		    'attribute' => 'sigsym_base_id',
		    'value' => ($model->sigsymBase ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigsymBase->sym_name, ['symbol/view', 'id' => $model->sigsymBase->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigsym_base_id' => $model->sigsym_base_id]])
		    :
		      '<span class="label label-warning">?</span>'),
		  ],
			[
		    'format' => 'html',
		    'attribute' => 'sigsym_quote_id',
		    'value' => ($model->sigsymQuote ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigsymQuote->sym_name, ['symbol/view', 'id' => $model->sigsymQuote->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigsym_quote_id' => $model->sigsym_quote_id]])
		    :
		      '<span class="label label-warning">?</span>'),
			],
			'sig_3callowedquotes:ntext',
      'sig_remarks:ntext',

      //'sig_lock',

      //'sig_createdat',
      'sig_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'sigusr_created_id',
    		'value' => ($model->sigusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigusrCreated->username, ['user/view', 'id' => $model->sigusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigusr_created_id' => $model->sigusr_created_id]])
        :
	        '<span class="label label-warning">?</span>'),
			],

      //'sig_updatedat',
      'sig_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'sigusr_updated_id',
    		'value' => ($model->sigusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigusrUpdated->username, ['user/view', 'id' => $model->sigusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigusr_updated_id' => $model->sigusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      //'sig_deletedat',
      'sig_deletedt',
      [
        'format' => 'html',
        'attribute' => 'sigusr_deleted_id',
        'value' => ($model->sigusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->sigusrDeleted->username, ['user/view', 'id' => $model->sigusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Signal'=>['sigusr_deleted_id' => $model->sigusr_deleted_id]])
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
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals',
        ['botsignal/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals',
        ['botsignal/create', 'Botsignal' => ['bsgsig_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Botsignals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Botsignals ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getBotsignals(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-botsignals',
        ]
    	]),
    	'pager' => [
        'class'          => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('cruds', 'First'),
        'lastPageLabel'  => Yii::t('cruds', 'Last')
    	],
    	'columns' => [
				[
    			'class'      => 'yii\grid\ActionColumn',
    			'template'   => '{view} {update}',
    			'contentOptions' => ['nowrap'=>'nowrap'],
    			'urlCreator' => function ($action, $model, $key, $index) {
        		// using the column name as key, not mapping to 'id' like the standard generator
        		$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        		$params[0] = 'botsignal' . '/' . $action;
        		$params['Botsignal'] = ['bsgsig_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'botsignal'
				],
        'id',
        'bsg_lock',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'bsgubt_id',
    			'value' => function ($model) {
        		if ($rel = $model->bsgubt) {
            	return Html::a($rel->ubt_name, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'bsgsym_id',
    			'value' => function ($model) {
        		if ($rel = $model->bsgsym) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'bsg_active',
        'bsg_name',
        'bsg_startdate',
        'bsg_enddate',
        'bsg_remarks:ntext',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Mbrs'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
			<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Mbrs',
		    ['membership/index'],
		    ['class'=>'btn text-muted btn-xs']
		  ); ?>
		  <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Mbrs',
		    ['membership/create'],
		    ['class'=>'btn btn-success btn-xs']
		  ); ?>
		  <?= Html::a('<span class="glyphicon glyphicon-link"></span> ' . Yii::t('cruds', 'Attach') . ' Mbr', ['signal-membership/create', 'SignalMembership'=>['sig_id'=>$model->id]],
		    ['class'=>'btn btn-info btn-xs']
		  ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Mbrs', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Mbrs ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
		  'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		  'dataProvider' => new \yii\data\ActiveDataProvider([
		    'query' => $model->getSignalMemberships(),
		    'pagination' => [
		      'pageSize' => 20,
		      'pageParam'=>'page-signalmemberships',
		    ]
		  ]),
		  'pager' => [
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
		        $params[0] = 'signal-membership' . '/' . $action;
		        $params['SignalMembership'] = ['sig_id' => $model->primaryKey()[0]];
		        return $params;
		      },
		      'buttons' => [ ],
		      'controller' => 'signal-membership'
		    ],
		    'id',
				[
		   		'class' => yii\grid\DataColumn::className(),
		   		'attribute' => 'mbr_id',
		   		'value' => function ($model) {
		      	if ($rel = $model->mbr) {
		          return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		        } else {
		          return '';
		        }
		      },
		      'format' => 'raw',
		    ],
		  ]
		]); ?>
	</div>'
  <?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('SignalMemberships'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		  <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signal Memberships',
		    ['signal-membership/index'],
		    ['class'=>'btn text-muted btn-xs']
		  ); ?>
		  <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signal Memberships',
		    ['signal-membership/create', 'SignalMembership' => ['sig_id' => $model->id]],
		    ['class'=>'btn btn-success btn-xs']
		  ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-SignalMemberships', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-SignalMemberships ul.pagination a, th a']) ?>
	<div class="table-responsive">
	  <?= \yii\grid\GridView::widget([
	    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
	    'dataProvider' => new \yii\data\ActiveDataProvider([
	      'query' => $model->getSignalMemberships(),
	      'pagination' => [
	        'pageSize' => 20,
	        'pageParam'=>'page-signalmemberships',
	      ]
	    ]),
	    'pager' => [
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
	       		$params[0] = 'signal-membership' . '/' . $action;
	       		$params['SignalMembership'] = ['sig_id' => $model->primaryKey()[0]];
	       		return $params;
	   			},
	   			'buttons' => [ ],
	   			'controller' => 'signal-membership'
				],
	      'id',
				[
	   			'class' => yii\grid\DataColumn::className(),
	   			'attribute' => 'mbr_id',
	   			'value' => function ($model) {
	       		if ($rel = $model->mbr) {
	            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
	       		} else {
	            return '';
	       		}
	   			},
	   			'format' => 'raw',
				],
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?= Tabs::widget([
    'id' => 'relation-tabs',
    'encodeLabels' => false,
    'items' => [
			[
		    'label'  => '<b class=""># '.Html::encode($model->id).'</b>',
		    'content' => $this->blocks['backend\models\Signal'],
		    'active' => true,
			],
			[
		    'content' => $this->blocks['Botsignals'],
		    'label'  => '<small>Botsignals <span class="badge badge-default">'. $model->getBotsignals()->count() . '</span></small>',
		    'active' => false,
			],
			[
		    'content' => $this->blocks['Mbrs'],
		    'label'  => '<small>Mbrs <span class="badge badge-default">'. $model->getMbrs()->count() . '</span></small>',
		    'active' => false,
			],
			[
		    'content' => $this->blocks['SignalMemberships'],
		    'label'  => '<small>Signal Memberships <span class="badge badge-default">'. $model->getSignalMemberships()->count() . '</span></small>',
		    'active' => false,
			],
		]
	]); ?>
</div>

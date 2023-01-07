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

$yesNos = GeneralHelper::getYesNos(false);
$memberships = GeneralHelper::getMembershipsForLanguage('');
$categories = GeneralHelper::getCategoriesOfType('sig', false, '');

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
      'sig_code',
      [
        'format' => 'html',
        'attribute' => 'sig_active',
        'value' => ''.$yesNos[$model->sig_active],
      ],
      [
        'format' => 'html',
        'attribute' => 'sig_active4admin',
        'value' => ''.$yesNos[$model->sig_active4admin],
      ],
      [
        'format' => 'html',
        'attribute' => 'sig_runenabled',
        'value' => ''.$yesNos[$model->sig_runenabled],
      ],
			'sig_maxbots',
      [
        'format' => 'html',
        'attribute' => 'sigcat_ids',
        'value' => function($model) use ($categories) {
          $result = [];
          if (!empty($model->sigcat_ids)) foreach(explode(',', $model->sigcat_ids) as $catid) {
            $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$categories[$catid], ['category/view', 'id'=>$catid]);
          }
          return implode("<br>\n", $result);
        },
      ],
			[
				'format' => 'html',
        'attribute' => 'sigmbr_ids',
        'value' => function($model) use ($memberships) {
					$result = [];
					if (!empty($model->sigmbr_ids)) foreach(explode(',', $model->sigmbr_ids) as $mbrid) {
						$result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$memberships[$mbrid], ['membership/view', 'id'=>$mbrid]);
					}
					return implode("<br>\n", $result);
				},
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
			//'sig_3callowedquotes:ntext',

			'sig_discordlogchanid',
			'sig_discordlogdelaychanid',
			'sig_discordmessage',
			'sig_discorddelayminutes',

      'sig_description:raw',
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
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals',
		           ['botsignal/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals',
		            ['botsignal/create', 'Botsignal' => ['bsgsig_id' => $model->id]],
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
		       $params['Botsignal'] = ['bsgsig_id' => $model->primaryKey()[0]];
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
		   'attribute' => 'bsgubt_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgubt) {
		           return Html::a($rel->id, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
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
		
		
		<?php $this->beginBlock('Bsgubts'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Bsgubts',
		           ['userbot/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Bsgubts',
		            ['userbot/create'],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-link"></span> ' . Yii::t('cruds', 'Attach') . ' Bsgubt', ['botsignal/create', 'Botsignal'=>['bsgsig_id'=>$model->id]],
		           ['class'=>'btn btn-info btn-xs']
		       ) ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Bsgubts', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Bsgubts ul.pagination a, th a']) ?>
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
		       $params['Botsignal'] = ['bsgsig_id' => $model->primaryKey()[0]];
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
		   'attribute' => 'bsgubt_id',
		   'value' => function ($model) {
		       if ($rel = $model->bsgubt) {
		           return Html::a($rel->id, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
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
		
		
		<?php $this->beginBlock('Signallogs'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signallogs',
		           ['signallog/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signallogs',
		            ['signallog/create', 'Signallog' => ['slgsig_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Signallogs', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signallogs ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getSignallogs(),
		       'pagination' => [
		           'pageSize' => 20,
		           'pageParam'=>'page-signallogs',
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
		       $params[0] = 'signallog' . '/' . $action;
		       $params['Signallog'] = ['slgsig_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'signallog'
		],
		       'id',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'slgbsg_id',
		   'value' => function ($model) {
		       if ($rel = $model->slgbsg) {
		           return Html::a($rel->id, ['botsignal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'slg_status',
		       'slg_alertmsg',
		       'slg_senddata',
		       'slg_message:ntext',
		       'slg_remarks:ntext',
		       'slg_lock',
		       'slg_createdat',
		]
		])
		. '</div>'
		?>
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
			/*[
		    'content' => $this->blocks['Mbrs'],
		    'label'  => '<small>Mbrs <span class="badge badge-default">'. $model->getMbrs()->count() . '</span></small>',
		    'active' => false,
			],
			[
		    'content' => $this->blocks['SignalMemberships'],
		    'label'  => '<small>Signal Memberships <span class="badge badge-default">'. $model->getSignalMemberships()->count() . '</span></small>',
		    'active' => false,
			],*/
			[
		    'content' => $this->blocks['Botsignals'],
		    'label'  => '<small>Botsignals <span class="badge badge-default">'. $model->getBotsignals()->count() . '</span></small>',
		    'active' => false,
			],
			[
		    'content' => $this->blocks['Bsgubts'],
		    'label'  => '<small>Bsgubts <span class="badge badge-default">'. $model->getBsgubts()->count() . '</span></small>',
		    'active' => false,
			],
			[
		    'content' => $this->blocks['Signallogs'],
		    'label'  => '<small>Signallogs <span class="badge badge-default">'. $model->getSignallogs()->count() . '</span></small>',
		    'active' => false,
			],
		]
	]); ?>
</div>

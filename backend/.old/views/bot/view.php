<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var backend\models\Bot $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Bot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Bot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud bot-view">

	<!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  	<span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      	<span aria-hidden="true">&times;</span>
			</button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

	<h1>
		<?= Html::encode($model->id) ?>
    <small><?= Yii::t('models', 'Bot') ?></small>
	</h1>

  <div class="clearfix crud-navigation">
		<!-- menu buttons -->
		<div class='pull-left'>
			<?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
          ['update', 'id' => $model->id], ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
          ['create', 'id' => $model->id, 'Bot'=>$copyParams], ['class' => 'btn btn-success'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
          ['create'], ['class' => 'btn btn-success'])
      ?>
    </div>

    <div class="pull-right">
      <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
        	. Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default'])
			?>
    </div>

  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\Bot'); ?>
  <?= DetailView::widget([
  	'model' => $model,
    'attributes' => [
			'bot_name',
			[
    		'format' => 'html',
    		'attribute' => 'botcat_id',
    		'value' => ($model->botcat ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->botcat->cat_title, ['category/view', 'id' => $model->botcat->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Bot'=>['botcat_id' => $model->botcat_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],
      'bot_3cbotid',
      'bot_dealstartjson:ntext',
      [
        'format' => 'html',
        'attribute' => 'botsym_cost_id',
        'value' => ($model->botsymCost ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->botsymCost->sym_name, ['symbol/view', 'id' => $model->botsymCost->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Bot'=>['botsym_cost_id' => $model->botsym_cost_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      'bot_costmonth',
			'bot_remarks:ntext',

      //'bot_lock',

      //'bot_createdat',
      'bot_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'botusr_created_id',
    		'value' => ($model->botusrCreated ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->botusrCreated->username, ['user/view', 'id' => $model->botusrCreated->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Bot'=>['botusr_created_id' => $model->botusr_created_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],
			//'bot_updatedat',
      'bot_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'botusr_updated_id',
    		'value' => ($model->botusrUpdated ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->botusrUpdated->username, ['user/view', 'id' => $model->botusrUpdated->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Bot'=>['botusr_updated_id' => $model->botusr_updated_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],
      //'bot_deletedat',
      'bot_deletedt',
      [
        'format' => 'html',
        'attribute' => 'botusr_deleted_id',
        'value' => ($model->botusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->botusrDeleted->username, ['user/view', 'id' => $model->botusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Bot'=>['botusr_deleted_id' => $model->botusr_deleted_id]])
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
   	]);
  ?>
  <?php $this->endBlock(); ?>

	<?php $this->beginBlock('Signallogs'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signallogs',
        ['signallog/index'],
        ['class'=>'btn text-muted btn-xs']);
			?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signallogs',
        ['signallog/create', 'Signallog' => ['slgbot_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']);
			?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Signallogs', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signallogs ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
   		'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
   		'dataProvider' => new \yii\data\ActiveDataProvider([
     		'query' => $model->getSignallogs(),
     		'pagination' => [
        	'pageSize' => 20,
         	'pageParam'=>'page-signallogs',
     		]
   		]),
   		'pager'        => [
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
       			$params[0] = 'signallog' . '/' . $action;
       			$params['Signallog'] = ['slgbot_id' => $model->primaryKey()[0]];
       			return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'signallog'
				],
       	'id',
       	'slg_lock',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'slgusr_Id',
    			'value' => function ($model) {
       			if ($rel = $model->slgusr) {
         			return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
       			} else {
         			return '';
       			}
    			},
    			'format' => 'raw',
				],
       	'slg_name',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'slgcat_id',
    			'value' => function ($model) {
       			if ($rel = $model->slgcat) {
         			return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
       			} else {
          		return '';
       			}
    			},
    			'format' => 'raw',
				],
       	'slg_message:ntext',
       	'slg_createdat',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'slgusr_created_id',
    			'value' => function ($model) {
       			if ($rel = $model->slgusrCreated) {
         			return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
       			} else {
         			return '';
       			}
    			},
    			'format' => 'raw',
				],
      	'slg_updatedat',
			]
		]) ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Usersignals'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usersignals',
        ['usersignal/index'],
        ['class'=>'btn text-muted btn-xs']);
			?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usersignals',
        ['usersignal/create', 'Usersignal' => ['usgbot_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']);
			?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Usersignals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usersignals ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
   		'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
   		'dataProvider' => new \yii\data\ActiveDataProvider([
       	'query' => $model->getUsersignals(),
       	'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-usersignals',
       	]
   		]),
   		'pager'        => [
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
       			$params[0] = 'usersignal' . '/' . $action;
       			$params['Usersignal'] = ['usgbot_id' => $model->primaryKey()[0]];
       			return $params;
   				},
   				'buttons' => [ ],
   				'controller' => 'usersignal'
				],
       	'id',
       	'usg_lock',
				[
   				'class' => yii\grid\DataColumn::className(),
   				'attribute' => 'usgusr_id',
   				'value' => function ($model) {
       			if ($rel = $model->usgusr) {
           		return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
       			} else {
           		return '';
       			}
   				},
   				'format' => 'raw',
				],
       	'usg_name',
       	'usg_emailtoken:email',
       	'usg_pair',
       	'usg_createdat',
				[
   				'class' => yii\grid\DataColumn::className(),
   				'attribute' => 'usgusr_created_id',
   				'value' => function ($model) {
       			if ($rel = $model->usgusrCreated) {
           		return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
       			} else {
           		return '';
       			}
   				},
   				'format' => 'raw',
				],
       	'usg_updatedat',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

  <?= Tabs::widget(
    [
      'id' => 'relation-tabs',
      'encodeLabels' => false,
      'items' => [
 				[
    			'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    			'content' => $this->blocks['backend\models\Bot'],
    			'active'  => true,
				],
				[
    			'content' => $this->blocks['Signallogs'],
    			'label'   => '<small>Signallogs <span class="badge badge-default">'. $model->getSignallogs()->count() . '</span></small>',
    			'active'  => false,
				],
				[
    			'content' => $this->blocks['Usersignals'],
    			'label'   => '<small>Usersignals <span class="badge badge-default">'. $model->getUsersignals()->count() . '</span></small>',
    			'active'  => false,
				],
 			]
    ]
  ); ?>
</div>

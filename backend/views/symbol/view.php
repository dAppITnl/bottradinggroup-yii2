<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$symbolTypes = GeneralHelper::getSymbolTypes();

/**
* @var yii\web\View $this
* @var backend\models\Symbol $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Symbol');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Symbol'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud symbol-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
			</button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Symbol') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a(
        '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a(
        '<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Symbol'=>$copyParams],
        ['class' => 'btn btn-success'])
      ?>

      <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
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

  <?php $this->beginBlock('backend\models\Symbol'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      [
        'attribute'=>'sym_type',
        'value' => $symbolTypes[$model->sym_type] // backend\models\Symbol::getSymTypeValueLabel($model->sym_type),
      ],
      [
        'attribute'=>'sym_ispair',
        'value' => $yesNos[$model->sym_ispair] // backend\models\Symbol::getSymTypeValueLabel($model->sym_type),
      ],
      [
        'attribute'=>'sym_isquote',
        'value' => $yesNos[$model->sym_isquote] // backend\models\Symbol::getSymTypeValueLabel($model->sym_type),
      ],
      'sym_code',
			'sym_symbol',
      'sym_name',
      'sym_html',
			[
    		'format' => 'html',
    		'attribute' => 'symsym_base_id',
    		'value' => ($model->symsymBase ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symsymBase->sym_code, ['symbol/view', 'id' => $model->symsymBase->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symsym_base_id' => $model->symsym_base_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'symsym_quote_id',
    		'value' => ($model->symsymQuote ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symsymQuote->sym_code, ['symbol/view', 'id' => $model->symsymQuote->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symsym_quote_id' => $model->symsym_quote_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'symsym_network_id',
    		'value' => ($model->symsymNetwork ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symsymNetwork->sym_code, ['symbol/view', 'id' => $model->symsymNetwork->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symsym_network_id' => $model->symsym_network_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      'sym_contractaddress',
      'sym_rateurl:ntext',
      'sym_description:ntext',
      'sym_remarks:ntext',


      'sym_lock',

      'sym_createdt',
      [
        'format' => 'html',
        'attribute' => 'symusr_created_id',
        'value' => ($model->symusrCreated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symusrCreated->username, ['user/view', 'id' => $model->symusrCreated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symusr_created_id' => $model->symusr_created_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      'sym_updatedat',
      'sym_updatedt',
      [
        'format' => 'html',
        'attribute' => 'symusr_updated_id',
        'value' => ($model->symusrUpdated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symusrUpdated->username, ['user/view', 'id' => $model->symusrUpdated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symusr_updated_id' => $model->symusr_updated_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

      'sym_deletedat',
      'sym_deletedt',
			[
    		'format' => 'html',
    		'attribute' => 'symusr_deleted_id',
    		'value' => ($model->symusrDeleted ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->symusrDeleted->username, ['user/view', 'id' => $model->symusrDeleted->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Symbol'=>['symusr_deleted_id' => $model->symusr_deleted_id]])
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

  <?php $this->beginBlock('Cryptoaddresses'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Cryptoaddresses',
		           ['cryptoaddress/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Cryptoaddresses',
		            ['cryptoaddress/create', 'Cryptoaddress' => ['cadsym_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Cryptoaddresses', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Cryptoaddresses ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
					'query' => $model->getCryptoaddresses(),
		      'pagination' => [
 						'pageSize' => 20,
		        'pageParam'=>'page-cryptoaddresses',
					]
			 ]),
		   'pager'      => [
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
		       $params[0] = 'cryptoaddress' . '/' . $action;
		       $params['Cryptoaddress'] = ['cadsym_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'cryptoaddress'
		],
		'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'cadusr_owner_id',
		   'value' => function ($model) {
		       if ($rel = $model->cadusrOwner) {
		           return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'cad_type',
		       'cadmbr_ids:ntext',
		       'cad_active',
		       'cad_payprovider',
		       'cad_ismainnet',
		       'cad_networkname',
		       'cad_networksettings',
		]
		])
		. '</div>'
		?>
		<?php Pjax::end() ?>
		<?php $this->endBlock(); ?>


	<?php $this->beginBlock('Pricelists'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Pricelists',
      	['pricelist/index'],
      	['class'=>'btn text-muted btn-xs']
    	); ?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Pricelists',
      	['pricelist/create', 'Pricelist' => ['prlsym_id' => $model->id]],
      	['class'=>'btn btn-success btn-xs']
    	); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Pricelists', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Pricelists ul.pagination a, th a']) ?>
	<div class="table-responsive">'
 		<?= \yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getPricelists(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-pricelists',
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
        		$params[0] = 'pricelist' . '/' . $action;
        		$params['Pricelist'] = ['prlsym_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'pricelist'
				],
        'id',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'prlmbr_id',
    			'value' => function ($model) {
        		if ($rel = $model->prlmbr) {
            	return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
       			}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'prlcat_id',
    			'value' => function ($model) {
        		if ($rel = $model->prlcat) {
            	return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'prl_active',
        'prl_name',
        'prl_discountcode',
        'prl_startdate',
        'prl_enddate',
        'prl_percode',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

  <?php $this->beginBlock('Signals'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signals',
		           ['signal/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signals',
		            ['signal/create', 'Signal' => ['sigsym_base_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Signals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signals ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getSignals(),
		       'pagination' => [
							'pageSize' => 20,
		          'pageParam'=>'page-signals',
					 ]
   		 ]),
		   'pager'      => [
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
		       $params[0] = 'signal' . '/' . $action;
		       $params['Signal'] = ['sigsym_base_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'signal'
		],
		 'id',
 	       'sigcat_ids:ntext',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'sigsym_quote_id',
		   'value' => function ($model) {
		       if ($rel = $model->sigsymQuote) {
		           return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'sigmbr_ids:ntext',
		       'sig_runenabled',
		       'sig_active',
		       'sig_maxbots',
		       'sig_code',
		       'sig_name',
		]
		])
		. '</div>'
		?>
		<?php Pjax::end() ?>
		<?php $this->endBlock() ?>

<?php $this->beginBlock('Signals0s'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signals0s',
		           ['signal/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signals0s',
		            ['signal/create', 'Signal' => ['sigsym_quote_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Signals0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signals0s ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getSignals0(),
		       'pagination' => [
  				 		'pageSize' => 20,
		       		'pageParam'=>'page-signals0s',
		       ]
   			]),
		   'pager'      => [
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
		       $params[0] = 'signal' . '/' . $action;
		       $params['Signal'] = ['sigsym_quote_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'signal'
		],
		       'id',
       'sigcat_ids:ntext',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'sigsym_base_id',
		   'value' => function ($model) {
		       if ($rel = $model->sigsymBase) {
		           return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'sigmbr_ids:ntext',
		       'sig_runenabled',
		       'sig_active',
		       'sig_maxbots',
		       'sig_code',
		       'sig_name',
		]
		])
		. '</div>'
		?>
		<?php Pjax::end() ?>
		<?php $this->endBlock() ?>


	<?php // $this->beginBlock('Symbols'); ?>
	<!-- div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= '' /*Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols',
        ['symbol/index'],
        ['class'=>'btn text-muted btn-xs']
      );*/ ?>
  		<?= '' /* Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols',
        ['symbol/create', 'Symbol' => ['symsym_network_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      );*/ ?>
		</div>
	</div -->

	<?php //Pjax::begin(['id'=>'pjax-Symbols', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols ul.pagination a, th a']) ?>
	<!-- div class="table-responsive">
		<?= '' /* \yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-symbols',
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
        		$params[0] = 'symbol' . '/' . $action;
        		$params['Symbol'] = ['symsym_network_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'symbol'
				],
        'id',
				[
          'attribute'=>'sym_type',
          'value' => function ($model) {
            return backend\models\Symbol::getSymTypeValueLabel($model->sym_type);
          }
        ],
        'sym_ispair',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'symsym_base_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymBase) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'symsym_quote_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymQuote) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'sym_contractaddress',
        'sym_code',
        'sym_name',
        'sym_html',
			]
		]);*/ ?>
	</div -->
	<?php //Pjax::end() ?>
	<?php //$this->endBlock() ?>

	<?php $this->beginBlock('Symbols0s'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols0s',
        ['symbol/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols0s',
        ['symbol/create', 'Symbol' => ['symsym_base_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Symbols0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols0s ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols0(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-symbols0s',
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
        		$params[0] = 'symbol' . '/' . $action;
        		$params['Symbol'] = ['symsym_base_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'symbol'
				],
        'id',
				[
          'attribute'=>'sym_type',
          'value' => function ($model) {
            return backend\models\Symbol::getSymTypeValueLabel($model->sym_type);
          }
        ],
        'sym_ispair',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'symsym_quote_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymQuote) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'symsym_network_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymNetwork) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'sym_contractaddress',
        'sym_code',
        'sym_name',
        'sym_html',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Symbols1s'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols1s',
        ['symbol/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols1s',
        ['symbol/create', 'Symbol' => ['symsym_quote_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Symbols1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols1s ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?=\yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols1(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-symbols1s',
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
        		$params[0] = 'symbol' . '/' . $action;
        		$params['Symbol'] = ['symsym_quote_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
   				'controller' => 'symbol'
				],
        'id',
				[
          'attribute'=>'sym_type',
          'value' => function ($model) {
            return backend\models\Symbol::getSymTypeValueLabel($model->sym_type);
          }
        ],
	      'sym_ispair',
				[
    			'class' => yii\grid\DataColumn::className(),
   				'attribute' => 'symsym_base_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymBase) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'symsym_network_id',
    			'value' => function ($model) {
        		if ($rel = $model->symsymNetwork) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'sym_contractaddress',
        'sym_code',
        'sym_name',
        'sym_html',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Userpays'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays',
        ['userpay/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays',
        ['userpay/create', 'Userpay' => ['upysym_pay_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Userpays', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
      'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
      'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-userpays',
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
        		$params[0] = 'userpay' . '/' . $action;
        		$params['Userpay'] = ['upysym_pay_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'userpay'
				],
        'id',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upyumb_id',
    			'value' => function ($model) {
        		if ($rel = $model->upyumb) {
            	return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upymbr_id',
    			'value' => function ($model) {
        		if ($rel = $model->upymbr) {
            	return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upycat_id',
    			'value' => function ($model) {
        		if ($rel = $model->upycat) {
            	return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'upy_state',
        'upy_name',
        'upy_percode',
        'upy_startdate',
        'upy_enddate',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Userpays0s'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays0s',
        ['userpay/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays0s',
        ['userpay/create', 'Userpay' => ['upysym_rate_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Userpays0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays0s ul.pagination a, th a']) ?>
 	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
      'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
      'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays0(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-userpays0s',
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
        		$params[0] = 'userpay' . '/' . $action;
        		$params['Userpay'] = ['upysym_rate_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'userpay'
				],
        'id',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upyumb_id',
    			'value' => function ($model) {
        		if ($rel = $model->upyumb) {
            	return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
   				'attribute' => 'upymbr_id',
    			'value' => function ($model) {
        		if ($rel = $model->upymbr) {
            	return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upycat_id',
    			'value' => function ($model) {
        		if ($rel = $model->upycat) {
            	return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
    		'upy_state',
    		'upy_name',
   			'upy_percode',
    		'upy_startdate',
    		'upy_enddate',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>


<?php $this->beginBlock('Userpays1s'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays1s',
		           ['userpay/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays1s',
		            ['userpay/create', 'Userpay' => ['upysym_rate_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div>
		<?php Pjax::begin(['id'=>'pjax-Userpays1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays1s ul.pagination a, th a']) ?>
		<?=
		'<div class="table-responsive">'
		. \yii\grid\GridView::widget([
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
		   'dataProvider' => new \yii\data\ActiveDataProvider([
		       'query' => $model->getUserpays1(),
		       'pagination' => [
		           'pageSize' => 20,
		           'pageParam'=>'page-userpays1s',
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
		       $params[0] = 'userpay' . '/' . $action;
		       $params['Userpay'] = ['upysym_rate_id' => $model->primaryKey()[0]];
		       return $params;
		   },
		   'buttons'   => [
		       
		   ],
		   'controller' => 'userpay'
		],
		       'id',
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'upyusr_id',
		   'value' => function ($model) {
		       if ($rel = $model->upyusr) {
		           return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'upyumb_id',
		   'value' => function ($model) {
		       if ($rel = $model->upyumb) {
		           return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'upymbr_id',
		   'value' => function ($model) {
		       if ($rel = $model->upymbr) {
		           return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'upycat_id',
		   'value' => function ($model) {
		       if ($rel = $model->upycat) {
		           return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
		[
		   'class' => yii\grid\DataColumn::className(),
		   'attribute' => 'upyprl_id',
		   'value' => function ($model) {
		       if ($rel = $model->upyprl) {
		           return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		       } else {
		           return '';
		       }
		   },
		   'format' => 'raw',
		],
		       'upy_state',
		       'upy_name',
		       'upy_percode',
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
    		'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    		'content' => $this->blocks['backend\models\Symbol'],
    		'active'  => true,
			],
			[
		    'content' => $this->blocks['Cryptoaddresses'],
		    'label'  => '<small>Cryptoaddresses <span class="badge badge-default">'. $model->getCryptoaddresses()->count() . '</span></small>',
		    'active' => false,
			],
			[
    		'content' => $this->blocks['Pricelists'],
    		'label'   => '<small>Pricelists <span class="badge badge-default">'. $model->getPricelists()->count() . '</span></small>',
    		'active'  => false,
			],
			[
		   'content' => $this->blocks['Signals'],
		   'label'  => '<small>Signals <span class="badge badge-default">'. $model->getSignals()->count() . '</span></small>',
		   'active' => false,
			],
			[
		    'content' => $this->blocks['Signals0s'],
		    'label'  => '<small>Signals0s <span class="badge badge-default">'. $model->getSignals0()->count() . '</span></small>',
		    'active' => false,
			],
			[
    		'content' => $this->blocks['Symbols0s'],
    		'label'   => '<small>Symbols0s <span class="badge badge-default">'. $model->getSymbols0()->count() . '</span></small>',
    		'active'  => false,
			],
			[
  		  'content' => $this->blocks['Symbols1s'],
    		'label'   => '<small>Symbols1s <span class="badge badge-default">'. $model->getSymbols1()->count() . '</span></small>',
    		'active'  => false,
			],
			[
    		'content' => $this->blocks['Userpays'],
    		'label'   => '<small>Userpays <span class="badge badge-default">'. $model->getUserpays()->count() . '</span></small>',
    		'active'  => false,
			],
			[
    		'content' => $this->blocks['Userpays0s'],
    		'label'   => '<small>Userpays0s <span class="badge badge-default">'. $model->getUserpays0()->count() . '</span></small>',
    		'active'  => false,
			],
			[
		    'content' => $this->blocks['Userpays1s'],
		    'label'  => '<small>Userpays1s <span class="badge badge-default">'. $model->getUserpays1()->count() . '</span></small>',
		    'active' => false,
			],
 		]
  ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use common\helpers\GeneralHelper;

$userpayStates = GeneralHelper::getUserpayStates();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$payProviders = GeneralHelper::getPayproviders();
//$userpayProvidertype =GeneralHelper::getUserpayProvidertype();

/**
* @var yii\web\View $this
* @var hftadmin\models\Userpay $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Userpay');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Userpay'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud userpay-view">

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

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Userpay') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info']);
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Userpay'=>$copyParams],
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

  <?php $this->beginBlock('hftadmin\models\Userpay'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'upy_name',
			[
		    'format' => 'html',
		    'attribute' => 'upyusr_id',
		    'value' => ($model->upyusr ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyusr->username, ['user/view', 'id' => $model->upyusr->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyusr_id' => $model->upyusr_id]])
		    :
		      '<span class="label label-warning">?</span>'),
			],
      [
        'format' => 'html',
        'attribute' => 'upyumb_id',
        'value' => ($model->upyumb ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['usermember/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyumb->umb_name, ['usermember/view', 'id' => $model->upyumb->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyumb_id' => $model->upyumb_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      [
        'format' => 'html',
        'attribute' => 'upymbr_id',
        'value' => ($model->upymbr ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['membership/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upymbr->mbr_title, ['membership/view', 'id' => $model->upymbr->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upymbr_id' => $model->upymbr_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      [
        'format' => 'html',
        'attribute' => 'upy_state',
        'value' => $userpayStates[$model->upy_state],
      ],
			[
        'format' => 'html',
        'attribute' => 'upycat_id',
        'value' => ($model->upycat ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upycat->cat_title, ['category/view', 'id' => $model->upycat->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upycat_id' => $model->upycat_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      'upy_paiddt',

      [
        'attribute' => 'upy_percode',
        'value' => $pricelistPeriods[ $model->upy_percode ],
      ],
      'upy_startdate:date',
      'upy_enddate:date',

			[
    		'format' => 'html',
    		'attribute' => 'upysym_pay_id',
    		'value' => ($model->upysymPay ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upysymPay->sym_code, ['symbol/view', 'id' => $model->upysymPay->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upysym_pay_id' => $model->upysym_pay_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
		    'format' => 'html',
		    'attribute' => 'upyprl_id',
		    'value' => ($model->upyprl ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['pricelist/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyprl->prl_name, ['pricelist/view', 'id' => $model->upyprl->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyprl_id' => $model->upyprl_id]])
		    :
		      '<span class="label label-warning">?</span>'),
		  ],
      'upy_payamount',

			[
		    'format' => 'html',
		    'attribute' => 'upysym_crypto_id',
		    'value' => ($model->upysymCrypto ?
		      Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
		      Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upysymCrypto->sym_code, ['symbol/view', 'id' => $model->upysymCrypto->id,]).' '.
		      Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upysym_crypto_id' => $model->upysym_crypto_id]])
		    :
		  	  '<span class="label label-warning">?</span>'),
			],
			'upy_cryptoamount',

			'upy_discountcode',
			[
    		'format' => 'html',
    		'attribute' => 'upysym_rate_id',
    		'value' => ($model->upysymRate ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upysymRate->sym_code, ['symbol/view', 'id' => $model->upysymRate->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upysym_rate_id' => $model->upysym_rate_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      'upy_rate',

      [
        'attribute'=>'upy_payprovider',
				'value' => $payProviders[$model->upy_payprovider],
      ],
      'upy_providername',
      'upy_fromaccount',
      'upy_toaccount',
			'upy_providerid',
      'upy_payref:ntext',
			'upy_providerid',
      'upy_payreply:ntext',

			'upy_maxsignals',

      'upy_remarks:ntext',

      //'upy_lock',
      //'upy_createdat',
      'upy_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'upyusr_created_id',
    		'value' => ($model->upyusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyusrCreated->username, ['user/view', 'id' => $model->upyusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyusr_created_id' => $model->upyusr_created_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      //'upy_updatedat',
      'upy_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'upyusr_updated_id',
    		'value' => ($model->upyusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyusrUpdated->username, ['user/view', 'id' => $model->upyusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyusr_updated_id' => $model->upyusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      //'upy_deletedat',
      'upy_deletedt',
      [
        'format' => 'html',
        'attribute' => 'upyusr_deleted_id',
        'value' => ($model->upyusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->upyusrDeleted->username, ['user/view', 'id' => $model->upyusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Userpay'=>['upyusr_deleted_id' => $model->upyusr_deleted_id]])
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

<?php $this->beginBlock('Usermembers'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usermembers',
		           ['usermember/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= '' /* Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usermembers',
		            ['usermember/create', 'Usermember' => ['umbupy_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       );*/ ?>
		</div>
		</div> 
<?php Pjax::begin(['id'=>'pjax-Usermembers', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usermembers ul.pagination a, th a']) ?> 
		<?= 
		'<div class="table-responsive">' 
		. \yii\grid\GridView::widget([ 
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>', 
		   'dataProvider' => new \yii\data\ActiveDataProvider([ 
		       'query' => $model->getUsermembers(), 
		       'pagination' => [ 
		           'pageSize' => 20, 
		           'pageParam'=>'page-usermembers', 
		       ] 
		   ]), 
		   'pager'       => [ 
		       'class'         => yii\widgets\LinkPager::className(), 
		       'firstPageLabel' => Yii::t('cruds', 'First'), 
		       'lastPageLabel' => Yii::t('cruds', 'Last') 
		   ], 
		   'columns' => [ 
		/*[ 
		   'class'     => 'yii\grid\ActionColumn', 
		   'template'  => '{view} {update}', 
		   'contentOptions' => ['nowrap'=>'nowrap'], 
		   'urlCreator' => function ($action, $model, $key, $index) { 
		       // using the column name as key, not mapping to 'id' like the standard generator 
		       $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key]; 
		       $params[0] = 'usermember' . '/' . $action; 
		       $params['Usermember'] = ['umbupy_id' => $model->primaryKey()[0]]; 
		       return $params; 
		   }, 
		   'buttons'   => [ 
		        
		   ], 
		   'controller' => 'usermember' 
		],*/ 
		       'id', 
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat 
		[ 
		   'class' => yii\grid\DataColumn::className(), 
		   'attribute' => 'umbusr_id', 
		   'value' => function ($model) { 
		       if ($rel = $model->umbusr) { 
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
		   'attribute' => 'umbmbr_id', 
		   'value' => function ($model) { 
		       if ($rel = $model->umbmbr) { 
		           return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]); 
		       } else { 
		           return ''; 
		       } 
		   }, 
		   'format' => 'raw', 
		], 
		// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat 
		/*[ 
		   'class' => yii\grid\DataColumn::className(), 
		   'attribute' => 'umbprl_id', 
		   'value' => function ($model) { 
		       if ($rel = $model->umbprl) { 
		           return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]); 
		       } else { 
		           return ''; 
		       } 
		   }, 
		   'format' => 'raw', 
		],*/ 
		       'umb_active', 
		       'umb_name', 
		       //'umb_roles', 
		       //'umb_startdate', 
		       //'umb_enddate', 
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
    		'content' => $this->blocks['hftadmin\models\Userpay'],
    		'active'  => true,
			],
			[
		    'content' => $this->blocks['Usermembers'],
		    'label'  => '<small>Usermembers <span class="badge badge-default">'. $model->getUsermembers()->count() . '</span></small>',
		    'active' => false,
  		],
 		]
  ]); ?>
</div>

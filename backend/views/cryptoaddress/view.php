<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use backend\models\Membership;
use backend\models\Cryptoaddress;
use common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos();
$payProviders = GeneralHelper::getPayproviders();

$memberships = Membership::getMemberships();

$networks = Cryptoaddress::getNetworks(false);
$networkNames = Cryptoaddress::getNetworkNames(false);


/**
* @var yii\web\View $this
* @var backend\models\Cryptoaddress $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Cryptoaddress');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Cryptoaddress'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud cryptoaddress-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
    <span class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
			</button>
      <?= \Yii::$app->session->getFlash('deleteError') ?>
    </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Cryptoaddress') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
	    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info']
			); ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Cryptoaddress'=>$copyParams],
        ['class' => 'btn btn-success']
			)?>

      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
        ['create'],
        ['class' => 'btn btn-success']
			)?>
    </div>

    <div class="pull-right">
      <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
        . Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
    </div>

  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\Cryptoaddress'); ?>
  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
			[
        'format' => 'html',
        'attribute' => 'cad_active',
        'value' => $yesNos[$model->cad_active],
      ],

      'cad_code',
      'cad_name',
      'cad_description:ntext',
      [
        'format' => 'html',
        'attribute' => 'cadusr_owner_id',
        'value' => ($model->cadusrOwner ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->cadusrOwner->username, ['user/view', 'id' => $model->cadusrOwner->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Cryptoaddress'=>['cadusr_owner_id' => $model->cadusr_owner_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],

			[
				'format' => 'html',
        'attribute' => 'cadmbr_ids',
				'value' => function($model) use ($memberships) {
					$result = '';
					foreach(explode(',', $model->cadmbr_ids) as $mbrid) {
						$result .= Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$memberships[ $mbrid ], ['membership/view', 'id' => $mbrid,])."<br>\n";
					}
					return $result;
				}
			],

			[
    		'format' => 'html',
    		'attribute' => 'cadsym_id',
    		'value' => ($model->cadsym ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->cadsym->sym_name, ['symbol/view', 'id' => $model->cadsym->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Cryptoaddress'=>['cadsym_id' => $model->cadsym_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      [
        'format' => 'html',
        'attribute' => 'cad_address',
        'value' => function($model) use ($networks) {
          $explorer = (!empty($model->cad_networkname) ? $networks[ $model->cad_networkname ]['explorer'] : '');
          return (!empty($explorer) ? "<a href='".$explorer."/address/".$model->cad_address."' target='_blank'>".$model->cad_address."</a>" : $model->cad_address);
        },
      ],
      'cad_memo',
      'cad_decimals',
      'cad_contract',

      [
      	'attribute'=>'cad_type',
        'value'=>backend\models\Cryptoaddress::getCadTypeValueLabel($model->cad_type),
      ],
			[
				'format' => 'html',
				'attribute' => 'cad_networkname',
				'value' => function($model) use ($networks) {
          $name = $networks[ $model->cad_networkname ]['name'];
          $explorer = (!empty($model->cad_networkname) ? $networks[ $model->cad_networkname ]['explorer'] : '');
          return (!empty($explorer) ? "<a href='".$explorer."' target='_blank'>".$name."</a>" : $name);
				},
			],
			'cad_networksettings',
			[
        'format' => 'html',
        'attribute' => 'cad_ismainnet',
        'value' => $yesNos[$model->cad_ismainnet],
      ],

			[
				'format' => 'raw',
				'attribute' => 'cad_payprovider',
				'value' => $payProviders[$model->cad_payprovider],
			],

      'cad_remarks:ntext',

      //'cad_lock',
      'cad_createdt',
      'cad_createdat',
			[
    		'format' => 'html',
    		'attribute' => 'cadusr_created_id',
    		'value' => ($model->cadusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->cadusrCreated->username, ['user/view', 'id' => $model->cadusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Cryptoaddress'=>['cadusr_created_id' => $model->cadusr_created_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      'cad_updatedt',
      'cad_updatedat',
			[
    		'format' => 'html',
    		'attribute' => 'cadusr_updated_id',
    		'value' => ($model->cadusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->cadusrUpdated->username, ['user/view', 'id' => $model->cadusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Cryptoaddress'=>['cadusr_updated_id' => $model->cadusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      'cad_deletedt',
      'cad_deletedat',
      [
        'format' => 'html',
        'attribute' => 'cadusr_deleted_id',
        'value' => ($model->cadusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->cadusrDeleted->username, ['user/view', 'id' => $model->cadusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Cryptoaddress'=>['cadusr_deleted_id' => $model->cadusr_deleted_id]])
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
    ]); ?>
  <?php $this->endBlock(); ?>

<?php $this->beginBlock('Userpays'); ?>
		<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
		 <?php
		       echo Html::a(
		           '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays',
		           ['userpay/index'],
		           ['class'=>'btn text-muted btn-xs']
		       ) ?>
		 <?= Html::a(
		           '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays',
		            ['userpay/create', 'Userpay' => ['upycad_to_id' => $model->id]],
		           ['class'=>'btn btn-success btn-xs']
		       ); ?>
		</div>
		</div> 
		<?php Pjax::begin(['id'=>'pjax-Userpays', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays ul.pagination a, th a']) ?> 
		<?= 
		'<div class="table-responsive">' 
		. \yii\grid\GridView::widget([ 
		   'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>', 
		   'dataProvider' => new \yii\data\ActiveDataProvider([ 
		       'query' => $model->getUserpays(), 
		       'pagination' => [ 
		           'pageSize' => 20, 
		           'pageParam'=>'page-userpays', 
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
		       $params['Userpay'] = ['upycad_to_id' => $model->primaryKey()[0]]; 
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
    		'content' => $this->blocks['backend\models\Cryptoaddress'],
    		'active'  => true,
			],
 			[
		    'content' => $this->blocks['Userpays'],
		    'label'  => '<small>Userpays <span class="badge badge-default">'. $model->getUserpays()->count() . '</span></small>',
		    'active' => false,
		  ],
 		]
  ]); ?>
</div>

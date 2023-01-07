<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use backend\models\Cryptoaddress;
use backend\models\Symbol;
use backend\models\Pricelist;
use \common\helpers\GeneralHelper;


$yesNos = GeneralHelper::getYesNos(false);
$pricelistPeriods = GeneralHelper::getPricelistPeriods();

$fiatSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_FIAT, true);
//$cryptoSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_CRYPTO, true);
//$cryptoAddresses = Cryptoaddress::getCryptoAddresses();
$cryptoaddressesSymbols = Cryptoaddress::getCryptoaddressesSymbols(true, true, true);
Yii::trace('** view pricelist cryptoaddressesSymbols: '.print_r($cryptoaddressesSymbols, true));

$quoteSymbol = '';
if (($model->prlsym->sym_type == Symbol::SYM_TYPE_CRYPTO) && ($model->prlsym->sym_ispair == 0)) {
	$quoteBasePrice = GeneralHelper::getQuoteBasePrice($model->prlsym->sym_code, 'EUR');
	if (empty($quoteBasePrice['error']) && !empty($quoteBasePrice['price']) && is_numeric($quoteBasePrice['price'])) {
		$quoteSymbol = $model->prlsym->sym_symbol;
		$quotePrice = $quoteBasePrice['price'];
		$calcPrice = ($model->prl_price / $quotePrice);
	}
}

/**
* @var yii\web\View $this
* @var backend\models\Pricelist $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Pricelist');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Pricelist'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud pricelist-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
		</button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'Pricelist') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Pricelist'=>$copyParams],
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

  <?php $this->beginBlock('backend\models\Pricelist'); ?>

  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'prl_name',
      [
        'format' => 'html',
        'attribute' => 'prl_active',
        'value' => $yesNos[$model->prl_active],
      ],
			[
        'format' => 'html',
        'attribute' => 'prl_active4admin',
        'value' => $yesNos[$model->prl_active4admin],
      ],
			'prl_pretext',
			'prl_posttext',
      'prl_startdate:date',
      'prl_enddate:date',
			[
    		'format' => 'html',
    		'attribute' => 'prlmbr_id',
    		'value' => ($model->prlmbr ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['membership/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlmbr->mbr_title, ['membership/view', 'id' => $model->prlmbr->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlmbr_id' => $model->prlmbr_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'prlcat_id',
    		'value' => ($model->prlcat ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['category/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlcat->cat_title, ['category/view', 'id' => $model->prlcat->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlcat_id' => $model->prlcat_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
    		'format' => 'html',
    		'attribute' => 'prlsym_id',
    		'value' => ($model->prlsym ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['symbol/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlsym->sym_name, ['symbol/view', 'id' => $model->prlsym->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlsym_id' => $model->prlsym_id]]).
					(!empty($quoteSymbol) ? ' -> '.$quoteSymbol.' '.$quotePrice : '')
        :
        	'<span class="label label-warning">?</span>'),
			],
			[
        'format' => 'html',
        'attribute' => 'prl_price',
        'value' => number_format($model->prl_price, 2, ',', '.') . (!empty($quoteSymbol) ? ' -> '.$quoteSymbol.' '.$calcPrice : ''),
      ],
			'prl_allowedtimes',
			'prl_discountcode',
			'prl_maxsignals',
      [
        'attribute' => 'prl_percode',
        'value' => $pricelistPeriods[ $model->prl_percode ],
      ],

			[
        'format' => 'html',
        //'attribute' => 'prlcad_crypto_ids',
				'label' => Yii::t('app', 'Allowed payable cryptoAddress coins'),
        'value' => function($model) use ($cryptoaddressesSymbols, $fiatSymbols) {
					if (!empty($model->prlcad_crypto_ids)) {
						$result = "<table>\n<tr><th>".Yii::t('app', 'CryproAddress coin')."</th><th>".Yii::t('app', 'Rate')."</th><th>".Yii::t('app', 'Crypto Price')."</th></tr>\n";
						$quoteBasePrice = [];
						foreach(explode(',', $model->prlcad_crypto_ids) as $nr => $cadid) {
							$quotecode = $cryptoaddressesSymbols[ $cadid ]['symcode'];
							if (empty($quoteBasePrice[$quotecode])) $quoteBasePrice[$quotecode] = GeneralHelper::getQuoteBasePrice($quotecode, $fiatSymbols[ $model->prlsym_id ]);
							$priceOk = !empty($quoteBasePrice[$quotecode]['price']);
							$cryptoPrice = ($priceOk ? $quoteBasePrice[$quotecode]['price'] : $quoteBasePrice[$quotecode]['error']);
							$price = ($priceOk ? ($model->prl_price / $cryptoPrice) : '');
							$result .= "<tr><td>".$cryptoaddressesSymbols[ $cadid ]['label'] ."</td><td".(!$priceOk ? " colspan='2'":"").">". $cryptoPrice .($priceOk ? "</td><td>".$price:""). "</td></tr>\n";
						}
						$result .= "</table>";
						return $result;
					} else {
						return null;
					}
				},
      ],

      'prl_remarks:ntext',

      //'prl_lock',

      //'prl_createdat',
      'prl_createdt',
			[
    		'format' => 'html',
    		'attribute' => 'prlusr_created_id',
    		'value' => ($model->prlusrCreated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlusrCreated->username, ['user/view', 'id' => $model->prlusrCreated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlusr_created_id' => $model->prlusr_created_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],

      //'prl_updatedat',
      'prl_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'prlusr_updated_id',
    		'value' => ($model->prlusrUpdated ?
       		Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
       		Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlusrUpdated->username, ['user/view', 'id' => $model->prlusrUpdated->id,]).' '.
       		Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlusr_updated_id' => $model->prlusr_updated_id]])
       	:
       		'<span class="label label-warning">?</span>'),
			],

      //'prl_deletedat',
      'prl_deletedt',
			[
    		'format' => 'html',
    		'attribute' => 'prlusr_deleted_id',
    		'value' => ($model->prlusrDeleted ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->prlusrDeleted->username, ['user/view', 'id' => $model->prlusrDeleted->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Pricelist'=>['prlusr_deleted_id' => $model->prlusr_deleted_id]])
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
		            ['usermember/create', 'Usermember' => ['umbprl_id' => $model->id]],
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
		       $params['Usermember'] = ['umbprl_id' => $model->primaryKey()[0]]; 
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
		       'umb_active', 
		       'umb_name', 
		       //'umb_roles', 
		       //'umb_startdate', 
		       //'umb_enddate', 
		       //'umb_maxsignals', 
		] 
		]) 
		. '</div>'  
		?> 
		<?php Pjax::end() ?> 
		<?php $this->endBlock() ?> 
		 
		 
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
		            ['userpay/create', 'Userpay' => ['upyprl_id' => $model->id]], 
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
		       $params['Userpay'] = ['upyprl_id' => $model->primaryKey()[0]]; 
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
		       'upy_state', 
		       'upy_name', 
		       'upy_percode', 
		       'upy_startdate', 
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
    			'content' => $this->blocks['backend\models\Pricelist'],
    			'active'  => true,
				],
				[
		      'content' => $this->blocks['Usermembers'],
		      'label'  => '<small>Usermembers <span class="badge badge-default">'. $model->getUsermembers()->count() . '</span></small>',
		      'active' => false,
		    ],
		    [
		      'content' => $this->blocks['Userpays'],
		      'label'  => '<small>Userpays <span class="badge badge-default">'. $model->getUserpays()->count() . '</span></small>',
		      'active' => false,
		    ],
			]
    ]); ?>
</div>

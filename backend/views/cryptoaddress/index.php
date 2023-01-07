<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Membership;
use backend\models\Cryptoaddress;
use common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$memberships = Membership::getMemberships();
$networks = Cryptoaddress::getNetworks(false);
$networkNames = Cryptoaddress::getNetworkNames(false);
$cadTypes = backend\models\Cryptoaddress::optsCadType();
$payProviders = GeneralHelper::getPayproviders();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\CryptoaddressSearch $searchModel
*/

$this->title = Yii::t('models', 'Cryptoaddress');
$this->params['breadcrumbs'][] = $this->title;

/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_cryptoaddress_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_cryptoaddress_update', ['route' => true])) {
   $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_cryptoaddress_delete', ['route' => true])) {
    $actionColumnTemplates[] = '{delete}';
//}
if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
  $actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']);
  $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud cryptoaddress-index">

  <?= '' /* $this->render('_search', ['model' =>$searchModel]); */ ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Cryptoaddress') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php  if (true || \Yii::$app->user->can('backend_cryptoaddress_create', ['route' => true])) { ?>
    <div class="pull-left">
      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
		<?php } ?>

    <div class="pull-right">
    	<?= \yii\bootstrap\ButtonDropdown::widget([
      	'id' => 'giiant-relations',
      	'encodeLabel' => false,
      	'label' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('cruds', 'Relations'),
      	'dropdown' => [
        	'options' => [
          	'class' => 'dropdown-menu-right'
        	],
        	'encodeLabels' => false,
        	'items' => [
          	[
            	'url' => ['symbol/index'],
            	'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Symbol'),
            ],
            [
              'url' => ['user/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'User'),
            ],
            [
              'url' => ['user/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'User'),
            ],
            [
              'url' => ['user/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'User'),
            ],
            [
              'url' => ['user/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'User'),
            ],
		  		]
        ],
        'options' => [
          'class' => 'btn-default'
        ]
      ]); ?>
    </div>
  </div>

  <hr />

  <div class="table-responsive">
  	<?= GridView::widget([
    	'dataProvider' => $dataProvider,
    	'pager' => [
      	'class' => yii\widgets\LinkPager::className(),
      	'firstPageLabel' => Yii::t('cruds', 'First'),
      	'lastPageLabel' => Yii::t('cruds', 'Last'),
    	],
    	'filterModel' => $searchModel,
    	'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
    	'headerRowOptions' => ['class'=>'x'],
    	'columns' => [
      	[
        	'class' => 'yii\grid\ActionColumn',
        	'template' => $actionColumnTemplateString,
        	'buttons' => [
          	'view' => function ($url, $model, $key) {
            	$options = [
              	'title' => Yii::t('cruds', 'View'),
              	'aria-label' => Yii::t('cruds', 'View'),
              	'data-pjax' => '0',
            	];
            	return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
          	}
        	],
        	'urlCreator' => function($action, $model, $key, $index) {
          	// using the column name as key, not mapping to 'id' like the standard generator
          	$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
          	$params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
          	return Url::toRoute($params);
        	},
        	'contentOptions' => ['nowrap'=>'nowrap']
      	],

				[
					'format' => 'raw',
          'attribute' => 'cad_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->cad_active ]; },
          'filter' => $yesNos,
				],
        'cad_code',
        'cad_name',


				[
		    	'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'cadsym_id',
			    'value' => function ($model) {
	  	      if ($rel = $model->cadsym) {
	    	      return Html::a($rel->sym_name, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			  	  } else {
			    	  return '';
			      }
				  },
				  'format' => 'raw',
				],
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'cadusr_owner_id',
		  	  'value' => function ($model) {
	      	  if ($rel = $model->cadusrOwner) {
          	  return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		        } else {
  	          return '';
	  	      }
		  	  },
		    	'format' => 'raw',
				],
				[
    	    'attribute'=>'cad_type',
      	  'value' => function ($model) use ($cadTypes) {
        	  return $cadTypes[ $model->cad_type ];
	        },
					'filter' => $cadTypes,
  	    ],
				[
					'class' => yii\grid\DataColumn::className(),
          'attribute' => 'cadmbr_ids',
          'value' => function($model) use ($memberships) {
            $result = [];
            if (!empty($model->cadmbr_ids)) foreach(explode(',', $model->cadmbr_ids) as $mbrid) {
              $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$memberships[$mbrid], ['membership/view', 'id'=>$mbrid], ['data-pjax' => 0]);
            }
            return implode(",<br>\n", $result);
          },
          'format' => 'raw',
				],
				[
					'format' => 'html',
        	'attribute' => 'cad_networkname',
        	'value' => function($model) use($networks) {
						$name = $networks[ $model->cad_networkname ]['name'];
						$explorer = (!empty($model->cad_networkname) ? $networks[ $model->cad_networkname ]['explorer'] : '');
						return (!empty($explorer) ? "<a href='".$explorer."' target='_blank'>".$name."</a>" : $name);
					}
      	],
				//'cad_networksettings',
				[
					'format' => 'html',
					'attribute' => 'cad_address',
					'value' => function($model) use ($networks) {
						$short = GeneralHelper::shortenAddress( $model->cad_address );
						$explorer = (!empty($model->cad_networkname) ? $networks[ $model->cad_networkname ]['explorer'] : '');
						return (!empty($explorer) ? "<a href='".$explorer."/address/".$model->cad_address."' target='_blank'>".$short."</a>" : $short);
					},
				],
				//'cad_memo',
				//'cad_decimals',
				//'cad_contract',

        [
          'format' => 'raw',
          'attribute' => 'cad_ismainnet',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->cad_ismainnet ]; },
          'filter' => $yesNos,
        ],

				[
					'format' => 'raw',
					'attribute'=>'cad_payprovider',
					'value' => function ($model) use ($payProviders) { return $payProviders[ $model->cad_payprovider ]; },
					'filter' => $payProviders,
				],

        //'cad_description:ntext',
        //'cad_remarks:ntext',
				//'cad_lock',

        'cad_createdt',
        //'cad_createdat',
				/*[
				  'class' => yii\grid\DataColumn::className(),
				  'attribute' => 'cadusr_created_id',
				  'value' => function ($model) {
			  	  if ($rel = $model->cadusrCreated) {
			    	  return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
				    } else {
				      return '';
				    }
				  },
			  	'format' => 'raw',
				],*/

        'cad_updatedt',
        //'cad_updatedat',
				/*[
				  'class' => yii\grid\DataColumn::className(),
				  'attribute' => 'cadusr_updated_id',
			  	'value' => function ($model) {
			    	 if ($rel = $model->cadusrUpdated) {
			      	  return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
				     } else {
				        return '';
				     }
				  },
			  	'format' => 'raw',
				],*/

        'cad_deletedt',
        //'cad_deletedat',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'cadusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->cadusrDeleted) {
              return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/
  	  ]
  	]); ?>
 	</div>
</div>
<?php \yii\widgets\Pjax::end() ?>

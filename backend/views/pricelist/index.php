<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Cryptoaddress;
use backend\models\Pricelist;
use backend\models\Symbol;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$pricelistPeriods = GeneralHelper::getPricelistPeriods();

//$fiatSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_FIAT, true);
//$cryptoSymbols = Pricelist::getSymbolsOfType( Symbol::SYM_TYPE_CRYPTO, true);
$cryptoaddressesSymbols = Cryptoaddress::getCryptoaddressesSymbols(true, true);

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\PricelistSearch $searchModel
*/

$this->title = Yii::t('models', 'Pricelist');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_pricelist_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_pricelist_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_pricelist_delete', ['route' => true])) {
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
<div class="giiant-crud pricelist-index">

  <?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Pricelist') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php if(true || \Yii::$app->user->can('backend_pricelist_create', ['route' => true])){ ?>
    <div class="pull-left">
       <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
		<?php } ?>

    <div class="pull-right">
	    <?= \yii\bootstrap\ButtonDropdown::widget(
  	    [
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
              'url' => ['category/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Category'),
            ],
            [
              'url' => ['membership/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Membership'),
            ],
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
        'prl_name',
        [
          'format' => 'raw',
          'attribute' => 'prl_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->prl_active ]; },
          'filter' => $yesNos,
        ],
				[
          'format' => 'raw',
          'attribute' => 'prl_active4admin',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->prl_active4admin ]; },
          'filter' => $yesNos,
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'prlcat_id',
          'value' => function ($model) {
            if ($rel = $model->prlcat) {
              return Html::a($rel->cat_title, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
				[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'prlmbr_id',
          'value' => function ($model) {
            if ($rel = $model->prlmbr) {
              return Html::a($rel->mbr_title, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
        'prl_startdate:date',
        'prl_enddate:date',
        [
          'attribute'=>'prl_percode',
          //'value' => function ($model) { return backend\models\Pricelist::getPrlPercodeValueLabel($model->prl_percode); }
					'value' => function($model) use ($pricelistPeriods) { return $pricelistPeriods[ $model->prl_percode ]; },
					'filter' => $pricelistPeriods
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'prlsym_id',
          'value' => function ($model) {
            if ($rel = $model->prlsym) {
              return Html::a($rel->sym_name, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
        'prl_price',
				'prl_allowedtimes',
				'prl_discountcode',
				[
					'class' => yii\grid\DataColumn::className(),
          'attribute' => 'prlcad_crypto_ids',
					'value' => function($model) use ($cryptoaddressesSymbols) {
            $result = [];
            if (!empty($model->prlcad_crypto_ids)) foreach(explode(',', $model->prlcad_crypto_ids) as $cadid) {
              $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$cryptoaddressesSymbols[$cadid], ['cryptoaddress/view', 'id'=>$cadid], ['data-pjax' => 0]);
            }
            return implode(",<br>\n", $result);
					},
					'format' => 'raw',
				],
				'prl_pretext',
        'prl_posttext',
        //'prl_remarks:ntext',

        /*'prl_lock',*/

				/*'prl_createdt',*/
        /*'prl_createdat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'prlusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->prlusrCreated) {
			        return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

				/*'prl_updatedt',*/
        /*'prl_updatedat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'prlusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->prlusrUpdated) {
			        return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

				/*'prl_deletedt',*/
        /*'prl_deletedat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'prlusr_deleted_id',
			    'value' => function ($model) {
			      if ($rel = $model->prlusrDeleted) {
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



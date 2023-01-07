<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\helpers\GeneralHelper;

$payCategories = GeneralHelper::getCategoriesOfType('pay', false);
$paySymbols = GeneralHelper::getSymbolCodes(false);
$userpayStates = GeneralHelper::getUserpayStates();
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$userpayProvidertype = GeneralHelper::getUserpayProvidertype();
$payProviders = GeneralHelper::getPayproviders();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var hftadmin\models\UserpaySearch $searchModel
*/

$this->title = Yii::t('models', 'Userpay');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('hftadmin_userpay_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('hftadmin_userpay_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('hftadmin_userpay_delete', ['route' => true])) {
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
<div class="giiant-crud userpay-index">

  <?php
		// echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Userpay') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php
			if(true || \Yii::$app->user->can('hftadmin_userpay_create', ['route' => true])){
		?>
    <div class="pull-left">
      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
    </div>
		<?php } ?>

		<?php if (false) { ?>
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
		          'url' => ['cryptoaddress/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Cryptoaddress'),
		        ],
            [
              'url' => ['category/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Category'),
            ],
            [
		          'url' => ['membership/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Membership'),
		        ],
						[
							'url' => ['pricelist/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Pricelist'),
		        ],
            [
              'url' => ['symbol/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Symbol'),
            ],
            [
              'url' => ['symbol/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Symbol'),
            ],
						[
		          'url' => ['symbol/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Symbol'),
		        ],
            [
		          'url' => ['usermember/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Usermember'),
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
						[
		           'url' => ['usermember/index'],
		           'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		        ],
          ]
        ],
        'options' => [
          'class' => 'btn-default'
        ]
      ]); ?>
    </div>
		<?php } ?>
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

        'upy_name',
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upyusr_id',
          'value' => function ($model) {
            if ($rel = $model->upyusr) {
              return Html::a($rel->usr_firstname.' '.$rel->usr_lastname/*username*/, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
          'filter' => '',
        ],

        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upyumb_id',
          'value' => function ($model) {
            if ($rel = $model->upyumb) {
              return Html::a($rel->umb_name, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
          'filter' => '',
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upymbr_id',
          'value' => function ($model) {
            if ($rel = $model->upymbr) {
              return Html::a($rel->mbr_title, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
          'filter' => '',
        ],*/
        [
          'format' => 'raw',
          'attribute' => 'upy_state',
          'value' => function($model) use ($userpayStates) { return $userpayStates[ $model->upy_state ]; },
          'filter' => $userpayStates,
        ],
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'upycat_id',
          'value' => function($model) use ($payCategories) { return $payCategories[ $model->upycat_id ]; },
			    / * 'value' => function ($model) {
			      if ($rel = $model->upycatState) {
			        return Html::a($rel->cat_title, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    }, * /
			    'format' => 'raw',
          'filter' => $payCategories,
				],*/

        [
          'attribute'=>'upy_percode',
          //'value' => function ($model) { return hftadmin\models\Pricelist::getPrlPercodeValueLabel($model->prl_percode); }
          'value' => function($model) use ($pricelistPeriods) { return $pricelistPeriods[ $model->upy_percode ]; },
          'filter' => $pricelistPeriods
        ],
				'upy_startdate:date',
        'upy_enddate:date',
				/*'upy_discountcode',
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upysym_pay_id',
          'value' => function($model) use ($paySymbols) { return $paySymbols[ $model->upysym_pay_id ]; },
          'format' => 'raw',
          'filter' => $paySymbols,
        ],
				[
		      'class' => yii\grid\DataColumn::className(),
		      'attribute' => 'upyprl_id',
		      'value' => function ($model) {
		        if ($rel = $model->upyprl) {
		          return Html::a($rel->prl_name, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
		        } else {
		          return '';
		        }
		      },
		      'format' => 'raw',
		    ],*/
				'upy_payamount',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upysym_rate_id',
          'value' => function($model) use ($paySymbols) { return $paySymbols[ $model->upysym_rate_id ]; },
          'format' => 'raw',
          'filter' => $paySymbols,
        ],

				[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upysym_crypto_id',
          'value' => function($model) use ($paySymbols) { return $paySymbols[ $model->upysym_crypto_id ]; },
          'format' => 'raw',
					'filter' => $paySymbols,
        ],
				'upy_cryptoamount',

        'upy_rate',
				*/
				'upy_paiddt:date',
        /*[
          'format' => 'raw',
          'attribute' => 'upy_payprovider',
          'value' => function($model) use ($payProviders) { return $payProviders[ $model->upy_payprovider ]; },
          'filter' => $payProviders,
        ],
				'upy_providername',
				'upy_fromaccount',
				'upy_toaccount',
				//'upy_providerid',
				//'upy_payref:ntext',
				//'upy_payreply:ntext',

				'upy_maxsignals',
				*/
        /*'upy_remarks:ntext',*/

        //'upy_lock',
        //'upy_createdat',
				//'upy_createdt:datetime',
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'upyusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->upyusrCreated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        //'upy_updatedat',
        //'upy_updatedt:datetime',
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'upyusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->upyusrUpdated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        /*'upy_deletedat',*/
        //'upy_deletedt:datetime',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'upyusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->upyusrDeleted) {
              return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
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



<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use \backend\models\Signal;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$memberships = GeneralHelper::getMembershipsForLanguage('', true);
$categories = GeneralHelper::getCategoriesOfType('sig', false, '');

$sig3CActiontexts = Signal::optsSig3cActionTexts();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\models\SignalSearch $searchModel
*/

$this->title = Yii::t('models', 'Signal');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_signal_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_signal_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_signal_delete', ['route' => true])) {
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
<div class="giiant-crud signal-index">

  <?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Signal') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php if(true || \Yii::$app->user->can('backend_signal_create', ['route' => true])){ ?>
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
  	            'url' => ['botsignal/index'],
    	          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Botsignal'),
      	      ],
        	    [
		      	    'url' => ['userbot/index'],
		        	  'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userbot'),
			        ],
			        [
			          'url' => ['signallog/index'],
		  	        'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signallog'),
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

        'sig_name',
        'sig_code',
        [
          'format' => 'raw',
          'attribute' => 'sig_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->sig_active ]; },
          'filter' => $yesNos,
        ],
				[
          'format' => 'raw',
          'attribute' => 'sig_active4admin',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->sig_active4admin ]; },
          'filter' => $yesNos,
        ],
        [
          'format' => 'raw',
          'attribute' => 'sig_runenabled',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->sig_runenabled ]; },
          'filter' => $yesNos,
        ],
				'sig_maxbots',
				[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'sigcat_ids',
          'value' => function ($model) use ($categories) {
            $result = [];
            if (!empty($model->sigcat_ids)) foreach(explode(',', $model->sigcat_ids) as $catid) {
              $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$categories[$catid], ['category/view', 'id'=>$catid], ['data-pjax' => 0]);
            }
            return implode(",<br>\n", $result);
          },
          'format' => 'html',
          'filter' => $categories,
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'sigmbr_ids',
          'value' => function ($model) use ($memberships) {
						$result = [];
						if (!empty($model->sigmbr_ids)) foreach(explode(',', $model->sigmbr_ids) as $mbrid) {
							$result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$memberships[$mbrid], ['membership/view', 'id'=>$mbrid], ['data-pjax' => 0]);
						}
						return implode(",<br>\n", $result);
          },
          'format' => 'html',
          'filter' => $memberships,
        ],
				[
          'format' => 'raw',
          'attribute' => 'sig_3cactiontext',
          'value' => function($model) use ($sig3CActiontexts) { return $sig3CActiontexts[ $model->sig_3cactiontext ]; },
          'filter' => $sig3CActiontexts,
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'sigsym_base_id',
          'value' => function ($model) {
            if ($rel = $model->sigsymBase) {
              return Html::a($rel->sym_code, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'sigsym_quote_id',
          'value' => function ($model) {
            if ($rel = $model->sigsymQuote) {
              return Html::a($rel->sym_code, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
				//'sig_3callowedquotes',
				'sig_discordlogchanid',
				'sig_discordlogdelaychanid',
				'sig_discordmessage',
				'sig_discorddelayminutes',
				/*'sig_description:ntext',*/
        /*'sig_remarks:ntext',*/

        //'sig_lock',

        //'sig_createdat',
        //'sig_createdt',
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'sigusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->sigusrCreated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        //'sig_updatedat',
        //'sig_updatedt',
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'sigusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->sigusrUpdated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        /*'sig_deletedt',*/
        /*'sig_deletedat',*/
        /*
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'sigusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->sigusrDeleted) {
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



<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\BotSearch $searchModel
*/

$this->title = Yii::t('models', 'Bot');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_bot_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_bot_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_bot_delete', ['route' => true])) {
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
<div class="giiant-crud bot-index">

    <?php
//             echo $this->render('_search', ['model' =>$searchModel]);
    ?>

    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1><?= Yii::t('models.plural', 'Bot') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>
    <div class="clearfix crud-navigation">

				<?php if(true || \Yii::$app->user->can('backend_bot_create', ['route' => true])){ ?>
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
                			'url' => ['signallog/index'],
                			'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signallog'),
            				],
                  	[
                			'url' => ['usersignal/index'],
                			'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usersignal'),
            				],
									]
            		],
            		'options' => [
            			'class' => 'btn-default'
            		]
            	]
						); ?>
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
                    'aria-label' => 'View '.Yii::t('cruds', 'View'),
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
						'bot_name',
						[
			    		'class' => yii\grid\DataColumn::className(),
			    		'attribute' => 'botcat_id',
			    		'value' => function ($model) {
			        	if ($rel = $model->botcat) {
			            return Html::a($rel->cat_title, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        	} else {
			            return '';
			        	}
			    		},
			    		'format' => 'raw',
						],
						'bot_3cbotid',
						'bot_dealstartjson:ntext',
            'bot_costmonth',
						'bot_createdt',
						'bot_updatedt',
						'bot_deletedt',

						//'bot_lock',
						/*[
			    		'class' => yii\grid\DataColumn::className(),
			    		'attribute' => 'botsym_cost_id',
			    		'value' => function ($model) {
			        	if ($rel = $model->botsymCost) {
			            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        	} else {
			            return '';
			        	}
			    		},
			    		'format' => 'raw',
						],*/
						/*'bot_deletedat',*/
						/*[
			    		'class' => yii\grid\DataColumn::className(),
			    		'attribute' => 'botusr_deleted_id',
			    		'value' => function ($model) {
			        	if ($rel = $model->botusrDeleted) {
			            return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        	} else {
			            return '';
			        	}
			    		},
			    		'format' => 'raw',
						],*/
						/*'bot_remarks:ntext',*/
						/*[
			    		'class' => yii\grid\DataColumn::className(),
			    		'attribute' => 'botusr_created_id',
			    		'value' => function ($model) {
			        	if ($rel = $model->botusrCreated) {
			            return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			        	} else {
			            return '';
			        	}
			    		},
			    		'format' => 'raw',
						],*/
						/*[
			    		'class' => yii\grid\DataColumn::className(),
			    		'attribute' => 'botusr_updated_id',
			    		'value' => function ($model) {
			        	if ($rel = $model->botusrUpdated) {
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



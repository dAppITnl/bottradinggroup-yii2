<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\UserbotSearch $searchModel
*/

$this->title = Yii::t('models', 'Userbot');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_userbot_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_userbot_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_userbot_delete', ['route' => true])) {
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
<div class="giiant-crud userbot-index">

  <?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Userbot') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php if (true || \Yii::$app->user->can('backend_userbot_create', ['route' => true])){ ?>
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
		            'url' => ['signal/index'],
		            'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signal'),
	            ],
            	[
                'url' => ['category/index'],
                'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Category'),
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

        'ubt_name',
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'ubtumb_id',
			    'value' => function ($model) {
			      if ($rel = $model->ubtumb) {
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
			    'attribute' => 'ubtcat_id',
			    'value' => function ($model) {
			      if ($rel = $model->ubtcat) {
			        return Html::a($rel->cat_title, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
					'filter' => '',
				],
				[
          'format' => 'raw',
          'attribute' => 'ubt_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->ubt_active ]; },
          'filter' => $yesNos,
				],
				[
          'format' => 'raw',
          'attribute' => 'ubt_signalstartstop',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->ubt_signalstartstop ]; },
          'filter' => $yesNos,
        ],
				[
          'format' => 'raw',
          'attribute' => 'ubt_userstartstop',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->ubt_userstartstop ]; },
          'filter' => $yesNos,
        ],

				'ubt_3cbotid',
				/*'ubt_3cdealstartjson:ntext',*/
				/*'ubt_remarks:ntext',*/

        //'ubt_lock',

        /*'ubt_createdat',*/
        /*'ubt_createdt',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'ubtusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->ubtusrCreated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/
        /*'ubt_updatedat',*/
        /*'ubt_updatedt',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'ubtusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->ubtusrUpdated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        /*'ubt_deletedat',*/
        /*'ubt_deletedt',*/
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'ubtusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->ubtusrDeleted) {
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



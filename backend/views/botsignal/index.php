<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Signal;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);

//$signals = Signal::getSignalsForUserBotsignal();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\models\BotsignalSearch $searchModel
*/

$this->title = Yii::t('models', 'Botsignal');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_botsignal_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_botsignal_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_botsignal_delete', ['route' => true])) {
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
<div class="giiant-crud botsignal-index">

  <?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Botsignal') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php if(true || \Yii::$app->user->can('backend_botsignal_create', ['route' => true])){ ?>
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
              'url' => ['bot/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Bot'),
            ],
            [
              'url' => ['signal/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Signal'),
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
              'url' => ['userpay/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Userpay'),
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

        //'bsg_name',
				[
					'format' => 'raw',
					'attribute' => 'bsg_active',
					'value' => function($model) use ($yesNos) { return $yesNos[ $model->bsg_active ]; },
					'filter' => $yesNos,
				],
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'bsgubt_id',
			    'value' => function ($model) {
			      if ($rel = $model->bsgubt) {
			        return Html::a($rel->ubt_name, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
					'filter' => '',
				],
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'bsgsig_ids',
          'value' => function ($model) use ($signals) {
            $result = [];
            if (!empty($model->bsgsig_ids)) foreach(explode(',', $model->bsgsig_ids) as $sigid) {
              $result[] = HTML::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$signals[$sigid], ['signal/view', 'id'=>$sigid], ['data-pjax' => 0]);
            }
            return implode(",<br>\n", $result);
          },
          'format' => 'html',
          //'filter' => $signals,
        ],*/
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'bsgsig_id',
			    'value' => function ($model) {
			      if ($rel = $model->bsgsig) {
			        return Html::a($rel->sig_name, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
          'filter' => '',
				],
				/*'bsg_remarks:ntext',*/

        //'bsg_lock',

        /*'bsg_createdat',*/
        /*'bsg_createdt',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'bsgusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->bsgusrCreated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			   	},
			    'format' => 'raw',
				],*/

        /*'bsg_updatedat',*/
        /*'bsg_updatedt',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'bsgusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->bsgusrUpdated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        //'bsg_deletedat',
        //'bsg_deletedt',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'bsgusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->bsgusrDeleted) {
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



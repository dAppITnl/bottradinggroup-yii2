<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\models\UsersignalSearch $searchModel
*/

$this->title = Yii::t('models', 'Usersignal');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_usersignal_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_usersignal_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_usersignal_delete', ['route' => true])) {
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
<div class="giiant-crud usersignal-index">

  <?php
		// echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Usersignal') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>
  <div class="clearfix crud-navigation">

		<?php
			if(true || \Yii::$app->user->can('backend_usersignal_create', ['route' => true])){
		?>
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
              'url' => ['bot/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Bot'),
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
        'usg_name',
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'usgbot_id',
          'value' => function ($model) {
            if ($rel = $model->usgbot) {
              return Html::a($rel->bot_name, ['bot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
        'usg_emailtoken:email',
        'usg_pair',
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'usgusr_id',
			    'value' => function ($model) {
			      if ($rel = $model->usgusr) {
			        return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],

        //'usg_lock',

				'usg_createdt',
				'usg_updatedt',
				'usg_deletedt',
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'usgusr_deleted_id',
			    'value' => function ($model) {
			      if ($rel = $model->usgusrDeleted) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/
				/*'usg_deletedat',*/
				/*'usg_remarks:ntext',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'usgusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->usgusrCreated) {
			        return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'usgusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->usgusrUpdated) {
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



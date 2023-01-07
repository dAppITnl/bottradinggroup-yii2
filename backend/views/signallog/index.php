<?php
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
//$signallogStates = GeneralHelper::getSignallogStates();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\SignallogSearch $searchModel
*/

$this->title = Yii::t('models', 'Signallog');
$this->params['breadcrumbs'][] = $this->title;

/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_signallog_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_signallog_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_signallog_delete', ['route' => true])) {
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
<div class="giiant-crud signallog-index">
	<?php
		// echo $this->render('_search', ['model' =>$searchModel]);
	?>

	<?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

	<h1><?= Yii::t('models.plural', 'Signallog') ?><small><?= Yii::t('cruds', 'List') ?></small></h1>

	<div class="clearfix crud-navigation">
		<?php
			if(\Yii::$app->user->can('backend_signallog_create', ['route' => true])){
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
		          'url' => ['botsignal/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Botsignal'),
		        ],
            [
		          'url' => ['signal/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Signal'),
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
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'slgsig_id',
          'value' => function ($model) {
            if ($rel = $model->slgsig) {
              return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
        [
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'slgbsg_id',
          'value' => function ($model) {
            if ($rel = $model->slgbsg) {
              return Html::a($rel->id/*bsgubt->ubt_name.'->'.$rel->bsgsig->sig_name*/, ['botsignal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],
				'slg_status',
        /*[
          'attribute'=>'slg_status',
          'value' => function ($model) use ($signallogStates) {
            return $signallogStates[$model->slg_status]; //backend\models\Signallog::getSlgStatusValueLabel($model->slg_status);
          },
        ],*/

        'slg_createdt:datetime',

				/*'slg_alertmsg',*/
        [
          'attribute' => 'slg_alertmsg',
          'contentOptions' => ['style'=>'white-space:normal'],
        ],
        //'slg_senddata:ntext',
				[
					'format' => 'raw',
					'attribute' => 'slg_senddata',
					'contentOptions' => ['style'=>'white-space:normal'],
					'value' => function ($model) {
            return '<pre style="line-height:1em; height:8em; max-width:250px; overflow:auto;">'
							.\yii\helpers\HtmlPurifier::process(json_encode(json_decode($model->slg_senddata), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							.'</pre>';
					},
				],
        //'slg_message:ntext',
				[
          'attribute' => 'slg_message',
          'contentOptions' => ['style'=>'white-space:normal'],
        ],

				'slg_discordlogmessage:ntext',
				'slg_discordlogchanid',
				'slg_discordtologat:datetime',
				'slg_discordlogdone:ntext',
				'slg_discordlogdelaydone:ntext',

        /*'slg_remarks:ntext',*/
				/*[
          'attribute' => 'slg_remarks',
          'contentOptions' => ['style'=>'white-space:normal'],
        ],*/

        //'slg_lock',

        //'slg_createdt',
        //'slg_createdat',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'slgusr_created_id',
          'value' => function ($model) {
            if ($rel = $model->slgusrCreated) {
              return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/

        //'slg_updatedt',
        //'slg_updatedat',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'slgusr_updated_id',
          'value' => function ($model) {
            if ($rel = $model->slgusrUpdated) {
              return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/

        //'slg_deletedt',
        //'slg_deletedat',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'slgusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->slgusrDeleted) {
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



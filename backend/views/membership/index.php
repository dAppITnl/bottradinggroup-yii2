<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
$languages = GeneralHelper::getLanguages();
//$membershipRoles = GeneralHelper::getMembershipRoles();
$discordRoles = GeneralHelper::getDiscordRoles();


//Yii::trace('YesNos '.print_r($yesNos,true));
//Yii::trace('languages '.print_r($languages,true));


/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
* @var backend\models\MembershipSearch $searchModel
*/

$this->title = Yii::t('models', 'Membership');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_membership_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_membership_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_membership_delete', ['route' => true])) {
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
<div class="giiant-crud membership-index">

	<?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
	?>

	<?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

	<h1><?= Yii::t('models.plural', 'Membership') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>
  <div class="clearfix crud-navigation">
		<?php if(true ||  \Yii::$app->user->can('backend_membership_create', ['route' => true])){ ?>
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
		             'url' => ['pricelist/index'],
		             'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Pricelist'),
		          ],
		          [
		             'url' => ['usermember/index'],
		             'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		          ],
		          [
		             'url' => ['userpay/index'],
		             'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userpay'),
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
        'mbr_code',
        'mbr_title',
        [
          'format' => 'raw',
          'attribute' => 'mbr_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->mbr_active ]; },
          'filter' => $yesNos,
        ],
        [
          'format' => 'raw',
          'attribute' => 'mbr_active4admin',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->mbr_active4admin ]; },
          'filter' => $yesNos,
        ],
        'mbr_order',
				[
          'format' => 'raw',
          'attribute' => 'mbr_language',
          'value' => function($model) use ($languages) { return $languages[ $model->mbr_language ]; },
          //'filter' => $languages,
        ],
				'mbr_groupnr',
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'mbrcat_id',
			    'value' => function ($model) {
	          if ($rel = $model->mbrcat) {
			        return Html::a($rel->cat_title, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],
				[
          'format' => 'html',
          'attribute' => 'mbr_discordroles',
          'value' => function($model) use ($discordRoles) {
						$result = [];
          	foreach(explode(',', $model->mbr_discordroles) as $role) $result[] = $discordRoles[ $role ];
          	return implode(',<br>', $result);
					},
          'filter' => $discordroles,
        ],
				'mbr_discordlogchanid',
			/*'mbr_cardbody:ntext',*/
				/*'mbr_detailbody:ntext',*/
				/*'mbr_remarks:ntext',*/

        //'mbr_lock',

        /*'mbr_createdt',*/
        /*'mbr_createdat',*/
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'mbrusr_created_id',
          'value' => function ($model) {
            if ($rel = $model->mbrusrCreated) {
              return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/

        /*'mbr_updatedt',*/
        /*'mbr_updatedat',*/
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'mbrusr_updated_id',
          'value' => function ($model) {
            if ($rel = $model->mbrusrUpdated) {
              return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/

				/*'mbr_deletedt',*/
        /*'mbr_deletedat',*/
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'mbrusr_deleted_id',
          'value' => function ($model) {
            if ($rel = $model->mbrusrDeleted) {
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



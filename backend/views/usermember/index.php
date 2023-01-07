<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use \common\helpers\GeneralHelper;

$yesNos = GeneralHelper::getYesNos(false);
//$getMembershipRoles = GeneralHelper::getMembershipRoles();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\models\UsermemberSearch $searchModel
*/

$this->title = Yii::t('models', 'Usermember');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_usermember_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_usermember_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

//if (\Yii::$app->user->can('backend_usermember_delete', ['route' => true])) {
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
<div class="giiant-crud usermember-index">

  <?php
		//             echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'Usermember') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<?php if(true || \Yii::$app->user->can('backend_usermember_create', ['route' => true])){ ?>
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
            	'url' => ['membership/index'],
            	'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Membership'),
          	],
						[
		           'url' => ['pricelist/index'],
		           'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Pricelist'),
		        ],
		        [
		           'url' => ['userpay/index'],
		           'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('models', 'Userpay'),
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
		          'url' => ['userbot/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userbot'),
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
        'umb_name',
        [
          'format' => 'raw',
          'attribute' => 'umb_active',
          'value' => function($model) use ($yesNos) { return $yesNos[ $model->umb_active ]; },
          'filter' => $yesNos,
        ],
     		/*[
        	'format' => 'raw',
        	'attribute' => 'umb_roles',
        	'value' => function($model) use ($getMembershipRoles) {
          	$result = [];
          	if (!empty($model->umb_roles)) {
            	$roles = explode(',', $model->umb_roles);
            	foreach($getMembershipRoles as $key => $value) {
              	if (in_array($key, $roles)) $result[] = $value;
            	}
          	}
          	return implode(', ', $result);
        	},
      	],*/
				//'umb_maxsignals',
        //'umb_startdate:date',
        //'umb_enddate:date',
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'umbprl_id',
          'value' => function ($model) {
            if ($rel = $model->umbprl) {
              return Html::a($rel->prl_name, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
              return '';
            }
          },
          'format' => 'raw',
        ],*/
        /*[
          'class' => yii\grid\DataColumn::className(),
          'attribute' => 'umbupy_id',
          'value' => function ($model) {
            if ($rel = $model->umbupy) {
              return Html::a($rel->id, ['userpay/view', 'id' => $rel->id,], ['data-pjax' => 0]);
            } else {
		          return '';
		        }
		      },
		      'format' => 'raw',
        ],*/
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'umbusr_id',
			    'value' => function ($model) {
	        	if ($rel = $model->umbusr) {
	            return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],
				[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'umbmbr_id',
			    'value' => function ($model) {
			      if ($rel = $model->umbmbr) {
			        return Html::a($rel->mbr_title, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			  	},
			    'format' => 'raw',
				],

        /*'umb_remarks:ntext',*/

        //'umb_lock',

        'umb_createdt',
        /*'umb_createdat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'umbusr_created_id',
			    'value' => function ($model) {
			      if ($rel = $model->umbusrCreated) {
			        return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        'umb_updatedt',
        /*'umb_updatedat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'umbusr_updated_id',
			    'value' => function ($model) {
			      if ($rel = $model->umbusrUpdated) {
			        return Html::a($rel->username, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
			      } else {
			        return '';
			      }
			    },
			    'format' => 'raw',
				],*/

        'umb_deletedt',
				/*'umb_deletedat',*/
				/*[
			    'class' => yii\grid\DataColumn::className(),
			    'attribute' => 'umbusr_deleted_id',
			    'value' => function ($model) {
			      if ($rel = $model->umbusrDeleted) {
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



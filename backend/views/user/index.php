<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use \common\helpers\GeneralHelper;

$siteLevels = GeneralHelper::getSiteLevels(false);
$siteCssfiles = GeneralHelper::getSiteCssFiles();
$languages = GeneralHelper::getLanguages();
$userStatusus = GeneralHelper::getUserStatuses();

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\models\UserSearch $searchModel
*/

$this->title = Yii::t('models', 'User');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

//if (\Yii::$app->user->can('backend_user_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
//}

//if (\Yii::$app->user->can('backend_user_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
//}

$actionColumnTemplates[] = '{discord}';

//if (\Yii::$app->user->can('backend_user_delete', ['route' => true])) {
    $actionColumnTemplates[] = '{delete}';
//}
if (isset($actionColumnTemplates)) {
	$actionColumnTemplate = implode(' ', $actionColumnTemplates);
  $actionColumnTemplateString = $actionColumnTemplate;
} else {
	Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']);
  $actionColumnTemplateString = "{view} {update} {discord} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud user-index">
  <?php
		// echo $this->render('_search', ['model' =>$searchModel]);
  ?>

  <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

  <h1><?= Yii::t('models.plural', 'User') ?> <small><?= Yii::t('cruds', 'List') ?></small></h1>

  <div class="clearfix crud-navigation">
		<div class="pull-left">
		<?php
			if(false && \Yii::$app->user->can('backend_user_create', ['route' => true])){
		?>
      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'), ['create'], ['class' => 'btn btn-success']) ?>
		<?php } else { ?>
		  New user only via <?= Html::a('SignUp', 'https://botsignals.bot-support.com/site/signup'); ?>.
		<?php } ?>
		</div>

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
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Botsignal'),
            ],
            [
              'url' => ['botsignal/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Botsignal'),
            ],
            [
              'url' => ['botsignal/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Botsignal'),
            ],
            [
              'url' => ['category/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Category'),
            ],
            [
              'url' => ['category/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Category'),
            ],
            [
              'url' => ['category/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Category'),
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
		          'url' => ['membership/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Membership'),
		        ],
		        [
		          'url' => ['membership/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Membership'),
		        ],
		        [
              'url' => ['membership/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Membership'),
		        ],
            [
		          'url' => ['pricelist/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Pricelist'),
		        ],
		        [
		          'url' => ['pricelist/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Pricelist'),
		        ],
		        [
		          'url' => ['pricelist/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Pricelist'),
		        ],
            [
              'url' => ['signallog/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signallog'),
            ],
            [
              'url' => ['signallog/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signallog'),
            ],
            [
              'url' => ['signallog/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signallog'),
            ],
            [
		          'url' => ['signal/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signal'),
		        ],
		        [
		          'url' => ['signal/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signal'),
		        ],
		        [
		          'url' => ['signal/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Signal'),
		        ],
            [
              'url' => ['symbol/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Symbol'),
            ],
            [
              'url' => ['symbol/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Symbol'),
            ],
            [
              'url' => ['symbol/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Symbol'),
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
		          'url' => ['userbot/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userbot'),
		        ],
		        [
		          'url' => ['userbot/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userbot'),
            ],
            [
		          'url' => ['usermember/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		        ],
		        [
		          'url' => ['usermember/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		        ],
		        [
		          'url' => ['usermember/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		        ],
            [
		          'url' => ['usermember/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Usermember'),
		        ],
            [
              'url' => ['userpay/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userpay'),
            ],
            [
              'url' => ['userpay/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userpay'),
            ],
            [
              'url' => ['userpay/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userpay'),
            ],
            [
              'url' => ['userpay/index'],
              'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'Userpay'),
            ],
            [
		          'url' => ['user/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'User'),
		        ],
		        [
		          'url' => ['user/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'User'),
		        ],
		        [
		          'url' => ['user/index'],
		          'label' => '<i class="glyphicon glyphicon-arrow-right"></i> ' . Yii::t('models', 'User'),
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
            },
            'discord' => function ($url, $model, $key) {
              $options = [
                'title' => Yii::t('cruds', 'Dcrd'),
                'aria-label' => Yii::t('cruds', 'Update Discord'),
                'data-pjax' => '0',
              ];
              return Html::a('<span class="glyphicon glyphicon-user-parents">Discord</span>', ['/user/setdiscordtoken', 'id' => $model->id], $options);
            },
          ],
          'urlCreator' => function($action, $model, $key, $index) {
            // using the column name as key, not mapping to 'id' like the standard generator
            $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
            $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
            return Url::toRoute($params);
          },
          'contentOptions' => ['nowrap'=>'nowrap']
        ],
				'username',
				[
					'format' => 'raw',
					'attribute' => 'usr_sitelevel',
					'value' => function($model) use ($siteLevels) { return $siteLevels[ $model->usr_sitelevel ]; },
					'filter' => $siteLevels,
				],
        [
          'format' => 'raw',
          'attribute' => 'usr_sitecsstheme',
          'value' => function($model) use ($siteCssfiles) { return $siteCssfiles[ $model->usr_sitecsstheme ]; },
          'filter' => $siteLevels,
        ],
				//'usr_password',
				//'password_hash',
        /*'password_reset_token',*/
        /*'verification_token',*/
				//'auth_key',
				'email:email',
				'usr_firstname',
				'usr_lastname',
				'usr_countrycode',
				[
          'format' => 'raw',
          'attribute' => 'usr_language',
          'value' => function($model) use ($languages) { return $languages[ $model->usr_language ]; },
          'filter' => $languages,
        ],
        'usr_moralisid',

				'usr_discordusername',
				'usr_discordnick',
				//'usr_discordid',
				//'usr_discordroles:ntext',
				//'usr_discordjoinedat',
        [
          'format' => 'raw',
          'attribute' => 'status',
          'value' => function($model) use ($userStatusus) { return $userStatusus[ $model->status ]; },
          'filter' => $userStatusus,
        ],

				//'usr_signalactive',
        /*'usr_remarks:ntext',*/

        //'usr_lock',
				'usr_createdt',
				'usr_updatedt',
        'usr_deletedt',
				/*'deleted_at',*/
				/*'deleted_by',*/
      ]
    ]); ?>
  </div>
</div>
<?php \yii\widgets\Pjax::end() ?>



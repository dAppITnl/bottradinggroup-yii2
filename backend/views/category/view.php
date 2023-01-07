<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$catTypes = GeneralHelper::getCategoryTypes();

/**
* @var yii\web\View $this
* @var backend\models\Category $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud category-view">
  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?><small><?= Yii::t('models', 'Category') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
		<div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'Category'=>$copyParams],
        ['class' => 'btn btn-success'])
      ?>

      <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
        ['create'],
        ['class' => 'btn btn-success'])
      ?>
    </div>

    <div class="pull-right">
      <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
        . Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default'])
      ?>
    </div>
  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\Category'); ?>
  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'cat_title',
			'cat_language',
      [
				'format' => 'html',
				'attribute' => 'cat_type',
				'value' => $catTypes[ $model->cat_type ],
			],
      'cat_description:ntext',
      'cat_remarks:ntext',

      //'cat_lock',
      //'cat_createdat',
			'cat_createdt',
      [
        'format' => 'html',
        'attribute' => 'catusr_created_id',
        'value' => ($model->catusrCreated ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->catusrCreated->username, ['user/view', 'id' => $model->catusrCreated->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Category'=>['catusr_created_id' => $model->catusr_created_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      //'cat_updatedat',
      'cat_updatedt',
			[
    		'format' => 'html',
    		'attribute' => 'catusr_updated_id',
    		'value' => ($model->catusrUpdated ?
        	Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
        	Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->catusrUpdated->username, ['user/view', 'id' => $model->catusrUpdated->id,]).' '.
        	Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Category'=>['catusr_updated_id' => $model->catusr_updated_id]])
        :
        	'<span class="label label-warning">?</span>'),
			],
      //'cat_deletedat',
      'cat_deletedt',
      [
        'format' => 'html',
        'attribute' => 'catusr_deleted_id',
        'value' => ($model->catusrDeleted ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->catusrDeleted->username, ['user/view', 'id' => $model->catusrDeleted->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Category'=>['catusr_deleted_id' => $model->catusr_deleted_id]])
        :
          '<span class="label label-warning">?</span>'),
      ],
    ],
  ]); ?>

  <hr/>

  <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('cruds', 'Delete'), ['delete', 'id' => $model->id],
    [
    	'class' => 'btn btn-danger',
    	'data-confirm' => '' . Yii::t('cruds', 'Are you sure to delete this item?') . '',
    	'data-method' => 'post',
    ]
	); ?>
  <?php $this->endBlock(); ?>

	<?php $this->beginBlock('Userpays'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
  		<?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays',
        ['userpay/index'],
        ['class'=>'btn text-muted btn-xs']
      ); ?>
  		<?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays',
        ['userpay/create', 'Userpay' => ['upycat_state_id' => $model->id]],
        ['class'=>'btn btn-success btn-xs']
      ); ?>
		</div>
	</div>

	<?php Pjax::begin(['id'=>'pjax-Userpays', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays ul.pagination a, th a']) ?>
	<div class="table-responsive">
		<?= \yii\grid\GridView::widget([
    	'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    	'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays(),
        'pagination' => [
          'pageSize' => 20,
          'pageParam'=>'page-userpays',
        ]
    	]),
    	'pager' => [
        'class'          => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('cruds', 'First'),
        'lastPageLabel'  => Yii::t('cruds', 'Last')
    	],
    	'columns' => [
				[
    			'class'      => 'yii\grid\ActionColumn',
    			'template'   => '{view} {update}',
    			'contentOptions' => ['nowrap'=>'nowrap'],
    			'urlCreator' => function ($action, $model, $key, $index) {
        		// using the column name as key, not mapping to 'id' like the standard generator
        		$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
        		$params[0] = 'userpay' . '/' . $action;
        		$params['Userpay'] = ['upycat_state_id' => $model->primaryKey()[0]];
        		return $params;
    			},
    			'buttons' => [ ],
    			'controller' => 'userpay'
				],
        'id',
        //'upy_lock',
        'upy_name',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upyusr_id',
    			'value' => function ($model) {
        		if ($rel = $model->upyusr) {
            	return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'upy_startdate:date',
        'upy_enddate:date',
        'upy_payamount',
				[
    			'class' => yii\grid\DataColumn::className(),
    			'attribute' => 'upysym_pay_id',
    			'value' => function ($model) {
        		if ($rel = $model->upysymPay) {
            	return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        		} else {
            	return '';
        		}
    			},
    			'format' => 'raw',
				],
        'upy_rate',
			]
		]); ?>
	</div>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?= Tabs::widget([
    'id' => 'relation-tabs',
    'encodeLabels' => false,
    'items' => [
			[
   			'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
   			'content' => $this->blocks['backend\models\Category'],
   			'active'  => true,
			],
			[
   			'content' => $this->blocks['Userpays'],
   			'label'   => '<small>Userpays <span class="badge badge-default">'. $model->getUserpays()->count() . '</span></small>',
   			'active'  => false,
			],
		]
  ]); ?>
</div>

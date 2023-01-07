<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use \common\helpers\GeneralHelper;

$siteLevels = GeneralHelper::getSiteLevels(false);
$siteCssfiles = GeneralHelper::getSiteCssFiles();
$languages = GeneralHelper::getLanguages();
$userStatusus = GeneralHelper::getUserStatuses();
$discordRoles = GeneralHelper::getDiscordRoles();

/**
* @var yii\web\View $this
* @var backend\models\User $model
*/
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models.plural', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud user-view">

  <!-- flash message -->
  <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
  <span class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <?= \Yii::$app->session->getFlash('deleteError') ?>
  </span>
  <?php endif; ?>

  <h1><?= Html::encode($model->id) ?> <small><?= Yii::t('models', 'User') ?></small></h1>

  <div class="clearfix crud-navigation">
    <!-- menu buttons -->
    <div class='pull-left'>
      <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
        [ 'update', 'id' => $model->id],
        ['class' => 'btn btn-info'])
      ?>
      <?= '' /*Html::a('<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
        ['create', 'id' => $model->id, 'User'=>$copyParams],
        ['class' => 'btn btn-success'])
      */?>
      <?= '' /*Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
        ['create'],
        ['class' => 'btn btn-success'])
      */?>
      <?= Html::a(Yii::t('app', 'Update Discord settings'),
        ['/user/setdiscordtoken', 'id' => $model->id],
        ['class' => 'btn btn-primary'])
      ?>
			<?= Html::a(Yii::t('app', 'View user details'),
				['/user/userdetail', 'id' => $model->id],
				['class' => 'btn btn-primary'])
			?>
			<?= Html::a(Yii::t('app', 'View Moralis details'),
				['/user/userdetailmoralis', 'id' => $model->id],
				['class' => 'btn btn-primary'])
			?>
    </div>

    <div class="pull-right">
      <?= Html::a('<span class="glyphicon glyphicon-list"></span> '
        . Yii::t('cruds', 'Full list'), ['index'], ['class'=>'btn btn-default']) ?>
    </div>
  </div>

  <hr/>

  <?php $this->beginBlock('backend\models\User'); ?>
  <?= DetailView::widget([
    'model' => $model,
    'attributes' => [
      'username',
      [
        'attribute'=>'usr_sitelevel',
        'value' => $siteLevels[ $model->usr_sitelevel ],
      ],
      [
        'attribute'=>'usr_sitecsstheme',
        'value' => $siteCssfiles[ $model->usr_sitecsstheme ] .' => '. GeneralHelper::createCssFilename($model->usr_sitecsstheme),
      ],
      //'usr_password',
      //'password_hash',
      //'password_reset_token',
      //'verification_token',
      //'auth_key',
      'email:email',
			'usr_firstname',
			'usr_lastname',
			'usr_countrycode',
      [
        'format' => 'html',
        'attribute' => 'status',
        'value' => $userStatusus[$model->status],
      ],
      [
        'format' => 'html',
        'attribute' => 'usr_language',
        'value' => $languages[$model->usr_language],
      ],
      'usr_moralisid',

      'usr_discordusername',
      'usr_discordnick',
      'usr_discordid',
      [
        'attribute' => 'usr_discordroles',
        'value' => function($model) use ($discordRoles) {
          $result = [];
          foreach(explode(',', $model->usr_discordroles) as $role) $result[] = $discordRoles[ $role ];
          return implode(', ', $result);
        }
      ],
      'usr_discordjoinedat',

      //'usr_signalactive',
      'usr_remarks:ntext',

      //'usr_lock',
      //'created_at',
      'usr_createdt',
      [
        'format' => 'html',
        'attribute' => 'created_by',
        'value' => ($model->createdBy ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->createdBy->username, ['user/view', 'id' => $model->createdBy->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'User'=>['created_by' => $model->created_by]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      //'updated_at',
      'usr_updatedt',
      [
        'format' => 'html',
        'attribute' => 'updated_by',
        'value' => ($model->updatedBy ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->updatedBy->username, ['user/view', 'id' => $model->updatedBy->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'User'=>['updated_by' => $model->updated_by]])
        :
          '<span class="label label-warning">?</span>'),
      ],
      //'deleted_at',
      'usr_deletedt',
      [
        'format' => 'html',
        'attribute' => 'deleted_by',
        'value' => ($model->deletedBy ?
          Html::a('<i class="glyphicon glyphicon-list"></i>', ['user/index']).' '.
          Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->deletedBy->username, ['user/view', 'id' => $model->deletedBy->id,]).' '.
          Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'User'=>['deleted_by' => $model->deleted_by]])
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
    ]);
  ?>

  <hr>

  <?= Html::a(Yii::t('app', 'Add / Replace 2FA'), ['create2fa', 'id' => $model->id],
    [
      'class' => 'btn btn-warning',
      'data-confirm' => '' . Yii::t('app', 'Are you sure to add (or replace an existing) 2FA code?') . '',
      'data-method' => 'post',
    ]);
  ?>
  <?php $this->endBlock(); ?>


<?php $this->beginBlock('Botsignals'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals',
            ['botsignal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals',
             ['botsignal/create', 'Botsignal' => ['bsgusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Botsignals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Botsignals ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getBotsignals(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-botsignals',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'botsignal' . '/' . $action;
        $params['Botsignal'] = ['bsgusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'botsignal'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgubt_id',
    'value' => function ($model) {
        if ($rel = $model->bsgubt) {
            return Html::a($rel->id, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgsig_id',
    'value' => function ($model) {
        if ($rel = $model->bsgsig) {
            return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'bsg_active',
        'bsg_remarks:ntext',
        'bsg_lock',
        'bsg_createdat',
        'bsg_createdt',
        'bsg_updatedat',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Botsignals0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals0s',
            ['botsignal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals0s',
             ['botsignal/create', 'Botsignal' => ['bsgusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Botsignals0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Botsignals0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getBotsignals0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-botsignals0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'botsignal' . '/' . $action;
        $params['Botsignal'] = ['bsgusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'botsignal'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgubt_id',
    'value' => function ($model) {
        if ($rel = $model->bsgubt) {
            return Html::a($rel->id, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgsig_id',
    'value' => function ($model) {
        if ($rel = $model->bsgsig) {
            return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'bsg_active',
        'bsg_remarks:ntext',
        'bsg_lock',
        'bsg_createdat',
        'bsg_createdt',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
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
],
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Botsignals1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Botsignals1s',
            ['botsignal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Botsignals1s',
             ['botsignal/create', 'Botsignal' => ['bsgusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Botsignals1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Botsignals1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getBotsignals1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-botsignals1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'botsignal' . '/' . $action;
        $params['Botsignal'] = ['bsgusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'botsignal'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgubt_id',
    'value' => function ($model) {
        if ($rel = $model->bsgubt) {
            return Html::a($rel->id, ['userbot/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'bsgsig_id',
    'value' => function ($model) {
        if ($rel = $model->bsgsig) {
            return Html::a($rel->id, ['signal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'bsg_active',
        'bsg_remarks:ntext',
        'bsg_lock',
        'bsg_createdat',
        'bsg_createdt',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
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
],
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Categories'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Categories',
            ['category/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Categories',
             ['category/create', 'Category' => ['catusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Categories', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Categories ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getCategories(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-categories',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'category' . '/' . $action;
        $params['Category'] = ['catusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'category'
],
        'id',
        'cat_type',
        'cat_language',
        'cat_order',
        'cat_title',
        'cat_description:ntext',
        'cat_remarks:ntext',
        'cat_lock',
        'cat_createdat',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Categories0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Categories0s',
            ['category/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Categories0s',
             ['category/create', 'Category' => ['catusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Categories0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Categories0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getCategories0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-categories0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'category' . '/' . $action;
        $params['Category'] = ['catusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'category'
],
        'id',
        'cat_type',
        'cat_language',
        'cat_order',
        'cat_title',
        'cat_description:ntext',
        'cat_remarks:ntext',
        'cat_lock',
        'cat_createdat',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Categories1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Categories1s',
            ['category/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Categories1s',
             ['category/create', 'Category' => ['catusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Categories1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Categories1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getCategories1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-categories1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'category' . '/' . $action;
        $params['Category'] = ['catusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'category'
],
        'id',
        'cat_type',
        'cat_language',
        'cat_order',
        'cat_title',
        'cat_description:ntext',
        'cat_remarks:ntext',
        'cat_lock',
        'cat_createdat',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Memberships'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Memberships',
            ['membership/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Memberships',
             ['membership/create', 'Membership' => ['mbrusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Memberships', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Memberships ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getMemberships(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-memberships',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'membership' . '/' . $action;
        $params['Membership'] = ['mbrusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'membership'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'mbrcat_id',
    'value' => function ($model) {
        if ($rel = $model->mbrcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'mbr_language',
        'mbr_active',
				'mbr_active4admin',
        'mbr_order',
        'mbr_code',
        'mbr_title',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Memberships0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Memberships0s',
            ['membership/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Memberships0s',
             ['membership/create', 'Membership' => ['mbrusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Memberships0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Memberships0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getMemberships0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-memberships0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'membership' . '/' . $action;
        $params['Membership'] = ['mbrusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'membership'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'mbrcat_id',
    'value' => function ($model) {
        if ($rel = $model->mbrcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'mbr_language',
        'mbr_active',
				'mbr_active4admin',
        'mbr_order',
        'mbr_code',
        'mbr_title',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Memberships1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Memberships1s',
            ['membership/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Memberships1s',
             ['membership/create', 'Membership' => ['mbrusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Memberships1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Memberships1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getMemberships1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-memberships1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'membership' . '/' . $action;
        $params['Membership'] = ['mbrusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'membership'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'mbrcat_id',
    'value' => function ($model) {
        if ($rel = $model->mbrcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'mbr_language',
        'mbr_active',
				'mbr_active4admin',
        'mbr_order',
        'mbr_code',
        'mbr_title',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Pricelists'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Pricelists',
            ['pricelist/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Pricelists',
             ['pricelist/create', 'Pricelist' => ['prlusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Pricelists', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Pricelists ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getPricelists(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-pricelists',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'pricelist' . '/' . $action;
        $params['Pricelist'] = ['prlusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'pricelist'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlmbr_id',
    'value' => function ($model) {
        if ($rel = $model->prlmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlcat_id',
    'value' => function ($model) {
        if ($rel = $model->prlcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'prl_active',
        'prl_name',
        'prl_startdate',
        'prl_enddate',
        'prl_percode',
        'prl_discountcode',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Pricelists0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Pricelists0s',
            ['pricelist/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Pricelists0s',
             ['pricelist/create', 'Pricelist' => ['prlusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Pricelists0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Pricelists0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getPricelists0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-pricelists0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'pricelist' . '/' . $action;
        $params['Pricelist'] = ['prlusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'pricelist'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlmbr_id',
    'value' => function ($model) {
        if ($rel = $model->prlmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlcat_id',
    'value' => function ($model) {
        if ($rel = $model->prlcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'prl_active',
        'prl_name',
        'prl_startdate',
        'prl_enddate',
        'prl_percode',
        'prl_discountcode',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Pricelists1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Pricelists1s',
            ['pricelist/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Pricelists1s',
             ['pricelist/create', 'Pricelist' => ['prlusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Pricelists1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Pricelists1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getPricelists1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-pricelists1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'pricelist' . '/' . $action;
        $params['Pricelist'] = ['prlusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'pricelist'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlmbr_id',
    'value' => function ($model) {
        if ($rel = $model->prlmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'prlcat_id',
    'value' => function ($model) {
        if ($rel = $model->prlcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'prl_active',
        'prl_name',
        'prl_startdate',
        'prl_enddate',
        'prl_percode',
        'prl_discountcode',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signallogs'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signallogs',
            ['signallog/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signallogs',
             ['signallog/create', 'Signallog' => ['slgusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signallogs', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signallogs ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignallogs(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signallogs',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signallog' . '/' . $action;
        $params['Signallog'] = ['slgusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signallog'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'slgbsg_id',
    'value' => function ($model) {
        if ($rel = $model->slgbsg) {
            return Html::a($rel->id, ['botsignal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'slg_status',
        'slg_senddata',
        'slg_message:ntext',
        'slg_remarks:ntext',
        'slg_lock',
        'slg_createdat',
        'slg_createdt',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signallogs0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signallogs0s',
            ['signallog/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signallogs0s',
             ['signallog/create', 'Signallog' => ['slgusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signallogs0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signallogs0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignallogs0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signallogs0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signallog' . '/' . $action;
        $params['Signallog'] = ['slgusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signallog'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'slgbsg_id',
    'value' => function ($model) {
        if ($rel = $model->slgbsg) {
            return Html::a($rel->id, ['botsignal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'slg_status',
        'slg_senddata',
        'slg_message:ntext',
        'slg_remarks:ntext',
        'slg_lock',
        'slg_createdat',
        'slg_createdt',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signallogs1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signallogs1s',
            ['signallog/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signallogs1s',
             ['signallog/create', 'Signallog' => ['slgusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signallogs1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signallogs1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignallogs1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signallogs1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signallog' . '/' . $action;
        $params['Signallog'] = ['slgusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signallog'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'slgbsg_id',
    'value' => function ($model) {
        if ($rel = $model->slgbsg) {
            return Html::a($rel->id, ['botsignal/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'slg_status',
        'slg_senddata',
        'slg_message:ntext',
        'slg_remarks:ntext',
        'slg_lock',
        'slg_createdat',
        'slg_createdt',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signals'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signals',
            ['signal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signals',
             ['signal/create', 'Signal' => ['sigusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signals', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signals ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignals(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signals',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signal' . '/' . $action;
        $params['Signal'] = ['sigusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signal'
],
        'id',
        'sigcat_ids:ntext',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sigmbr_ids:ntext',
        'sig_active',
				'sig_active4admin',
        'sig_maxbots',
        'sig_code',
        'sig_name',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signals0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signals0s',
            ['signal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signals0s',
             ['signal/create', 'Signal' => ['sigusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signals0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signals0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignals0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signals0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signal' . '/' . $action;
        $params['Signal'] = ['sigusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signal'
],
        'id',
        'sigcat_ids:ntext',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sigmbr_ids:ntext',
        'sig_active',
				'sig_active4admin',
        'sig_maxbots',
        'sig_code',
        'sig_name',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Signals1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Signals1s',
            ['signal/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Signals1s',
             ['signal/create', 'Signal' => ['sigusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Signals1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Signals1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSignals1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-signals1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'signal' . '/' . $action;
        $params['Signal'] = ['sigusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'signal'
],
        'id',
        'sigcat_ids:ntext',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'sigsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->sigsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sigmbr_ids:ntext',
        'sig_active',
				'sig_active4admin',
        'sig_maxbots',
        'sig_code',
        'sig_name',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Symbols'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols',
            ['symbol/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols',
             ['symbol/create', 'Symbol' => ['symusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Symbols', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-symbols',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'symbol' . '/' . $action;
        $params['Symbol'] = ['symusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'symbol'
],
        'id',
        'sym_type',
        'sym_isquote',
        'sym_ispair',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->symsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->symsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_network_id',
    'value' => function ($model) {
        if ($rel = $model->symsymNetwork) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sym_contractaddress',
        'sym_code',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Symbols0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols0s',
            ['symbol/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols0s',
             ['symbol/create', 'Symbol' => ['symusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Symbols0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-symbols0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'symbol' . '/' . $action;
        $params['Symbol'] = ['symusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'symbol'
],
        'id',
        'sym_type',
        'sym_isquote',
        'sym_ispair',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->symsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->symsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_network_id',
    'value' => function ($model) {
        if ($rel = $model->symsymNetwork) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sym_contractaddress',
        'sym_code',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Symbols1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Symbols1s',
            ['symbol/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Symbols1s',
             ['symbol/create', 'Symbol' => ['symusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Symbols1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Symbols1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getSymbols1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-symbols1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'symbol' . '/' . $action;
        $params['Symbol'] = ['symusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'symbol'
],
        'id',
        'sym_type',
        'sym_isquote',
        'sym_ispair',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_base_id',
    'value' => function ($model) {
        if ($rel = $model->symsymBase) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_quote_id',
    'value' => function ($model) {
        if ($rel = $model->symsymQuote) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'symsym_network_id',
    'value' => function ($model) {
        if ($rel = $model->symsymNetwork) {
            return Html::a($rel->id, ['symbol/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'sym_contractaddress',
        'sym_code',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userbots'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userbots',
            ['userbot/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userbots',
             ['userbot/create', 'Userbot' => ['ubtusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userbots', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userbots ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserbots(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userbots',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'userbot' . '/' . $action;
        $params['Userbot'] = ['ubtusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userbot'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtumb_id',
    'value' => function ($model) {
        if ($rel = $model->ubtumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtcat_id',
    'value' => function ($model) {
        if ($rel = $model->ubtcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'ubt_active',
        'ubt_name',
        'ubt_3cbotid',
        'ubt_3cdealstartjson:ntext',
        'ubt_remarks:ntext',
        'ubt_lock',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userbots0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userbots0s',
            ['userbot/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userbots0s',
             ['userbot/create', 'Userbot' => ['ubtusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userbots0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userbots0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserbots0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userbots0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'userbot' . '/' . $action;
        $params['Userbot'] = ['ubtusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userbot'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtumb_id',
    'value' => function ($model) {
        if ($rel = $model->ubtumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtcat_id',
    'value' => function ($model) {
        if ($rel = $model->ubtcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'ubt_active',
        'ubt_name',
        'ubt_3cbotid',
        'ubt_3cdealstartjson:ntext',
        'ubt_remarks:ntext',
        'ubt_lock',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userbots1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userbots1s',
            ['userbot/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userbots1s',
             ['userbot/create', 'Userbot' => ['ubtusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userbots1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userbots1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserbots1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userbots1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'userbot' . '/' . $action;
        $params['Userbot'] = ['ubtusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userbot'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtumb_id',
    'value' => function ($model) {
        if ($rel = $model->ubtumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'ubtcat_id',
    'value' => function ($model) {
        if ($rel = $model->ubtcat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'ubt_active',
        'ubt_name',
        'ubt_3cbotid',
        'ubt_3cdealstartjson:ntext',
        'ubt_remarks:ntext',
        'ubt_lock',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Usermembers'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usermembers',
            ['usermember/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usermembers',
             ['usermember/create', 'Usermember' => ['umbusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Usermembers', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usermembers ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsermembers(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-usermembers',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'usermember' . '/' . $action;
        $params['Usermember'] = ['umbusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'usermember'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbusr_id',
    'value' => function ($model) {
        if ($rel = $model->umbusr) {
            return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbmbr_id',
    'value' => function ($model) {
        if ($rel = $model->umbmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
/*[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbprl_id',
    'value' => function ($model) {
        if ($rel = $model->umbprl) {
            return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],*/
        'umb_active',
        'umb_name',
        //'umb_roles',
        //'umb_startdate',
        //'umb_enddate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Usermembers0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usermembers0s',
            ['usermember/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usermembers0s',
             ['usermember/create', 'Usermember' => ['umbusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Usermembers0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usermembers0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsermembers0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-usermembers0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'usermember' . '/' . $action;
        $params['Usermember'] = ['umbusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'usermember'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbusr_id',
    'value' => function ($model) {
        if ($rel = $model->umbusr) {
            return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbmbr_id',
    'value' => function ($model) {
        if ($rel = $model->umbmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
/*[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbprl_id',
    'value' => function ($model) {
        if ($rel = $model->umbprl) {
            return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],*/
        'umb_active',
        'umb_name',
        //'umb_roles',
        //'umb_startdate',
        //'umb_enddate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Usermembers1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usermembers1s',
            ['usermember/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usermembers1s',
             ['usermember/create', 'Usermember' => ['umbusr_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Usermembers1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usermembers1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsermembers1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-usermembers1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'usermember' . '/' . $action;
        $params['Usermember'] = ['umbusr_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'usermember'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbmbr_id',
    'value' => function ($model) {
        if ($rel = $model->umbmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
/*[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbprl_id',
    'value' => function ($model) {
        if ($rel = $model->umbprl) {
            return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],*/
        'umb_active',
        'umb_name',
        //'umb_roles',
        //'umb_startdate',
        //'umb_enddate',
        //'umb_maxsignals',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Usermembers2s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Usermembers2s',
            ['usermember/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Usermembers2s',
             ['usermember/create', 'Usermember' => ['umbusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Usermembers2s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Usermembers2s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsermembers2(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-usermembers2s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'usermember' . '/' . $action;
        $params['Usermember'] = ['umbusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'usermember'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbusr_id',
    'value' => function ($model) {
        if ($rel = $model->umbusr) {
            return Html::a($rel->id, ['user/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbmbr_id',
    'value' => function ($model) {
        if ($rel = $model->umbmbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
/*[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'umbprl_id',
    'value' => function ($model) {
        if ($rel = $model->umbprl) {
            return Html::a($rel->id, ['pricelist/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],*/
        'umb_active',
        'umb_name',
        //'umb_roles',
        //'umb_startdate',
        //'umb_enddate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userpays'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays',
            ['userpay/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays',
             ['userpay/create', 'Userpay' => ['upyusr_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userpays', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userpays',
        ]
    ]),
    'pager'        => [
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
        $params['Userpay'] = ['upyusr_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userpay'
],
        'id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upyumb_id',
    'value' => function ($model) {
        if ($rel = $model->upyumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upymbr_id',
    'value' => function ($model) {
        if ($rel = $model->upymbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upycat_id',
    'value' => function ($model) {
        if ($rel = $model->upycat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'upy_state',
        'upy_name',
        'upy_percode',
        'upy_startdate',
        'upy_enddate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userpays0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays0s',
            ['userpay/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays0s',
             ['userpay/create', 'Userpay' => ['upyusr_created_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userpays0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userpays0s',
        ]
    ]),
    'pager'        => [
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
        $params['Userpay'] = ['upyusr_created_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userpay'
],
        'id',
        'upyusr_id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upyumb_id',
    'value' => function ($model) {
        if ($rel = $model->upyumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upymbr_id',
    'value' => function ($model) {
        if ($rel = $model->upymbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upycat_id',
    'value' => function ($model) {
        if ($rel = $model->upycat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'upy_state',
        'upy_name',
        'upy_percode',
        'upy_startdate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userpays1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays1s',
            ['userpay/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays1s',
             ['userpay/create', 'Userpay' => ['upyusr_deleted_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userpays1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userpays1s',
        ]
    ]),
    'pager'        => [
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
        $params['Userpay'] = ['upyusr_deleted_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userpay'
],
        'id',
        'upyusr_id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upyumb_id',
    'value' => function ($model) {
        if ($rel = $model->upyumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upymbr_id',
    'value' => function ($model) {
        if ($rel = $model->upymbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upycat_id',
    'value' => function ($model) {
        if ($rel = $model->upycat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'upy_state',
        'upy_name',
        'upy_percode',
        'upy_startdate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Userpays2s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Userpays2s',
            ['userpay/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Userpays2s',
             ['userpay/create', 'Userpay' => ['upyusr_updated_id' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Userpays2s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Userpays2s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUserpays2(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-userpays2s',
        ]
    ]),
    'pager'        => [
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
        $params['Userpay'] = ['upyusr_updated_id' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'userpay'
],
        'id',
        'upyusr_id',
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upyumb_id',
    'value' => function ($model) {
        if ($rel = $model->upyumb) {
            return Html::a($rel->id, ['usermember/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upymbr_id',
    'value' => function ($model) {
        if ($rel = $model->upymbr) {
            return Html::a($rel->id, ['membership/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
[
    'class' => yii\grid\DataColumn::className(),
    'attribute' => 'upycat_id',
    'value' => function ($model) {
        if ($rel = $model->upycat) {
            return Html::a($rel->id, ['category/view', 'id' => $rel->id,], ['data-pjax' => 0]);
        } else {
            return '';
        }
    },
    'format' => 'raw',
],
        'upy_state',
        'upy_name',
        'upy_percode',
        'upy_startdate',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Users'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Users',
            ['user/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Users',
             ['user/create', 'User' => ['created_by' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Users', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Users ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsers(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-users',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'user' . '/' . $action;
        $params['User'] = ['created_by' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'user'
],
        'id',
        'usr_language',
        'status',
        'username',
        'usr_password',
        'usr_2fasecret',
        'email:email',
			[
                'attribute'=>'usr_sitelevel',
                'value' => function ($model) {
                    return backend\models\User::getUsrSitelevelValueLabel($model->usr_sitelevel);
                }    
            ]        ,
        'usr_sitecsstheme',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Users0s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Users0s',
            ['user/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Users0s',
             ['user/create', 'User' => ['deleted_by' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Users0s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Users0s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsers0(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-users0s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'user' . '/' . $action;
        $params['User'] = ['deleted_by' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'user'
],
        'id',
        'usr_language',
        'status',
        'username',
        'usr_password',
        'usr_2fasecret',
        'email:email',
			[
                'attribute'=>'usr_sitelevel',
                'value' => function ($model) {
                    return backend\models\User::getUsrSitelevelValueLabel($model->usr_sitelevel);
                }    
            ]        ,
        'usr_sitecsstheme',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('Users1s'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php
        echo Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Users1s',
            ['user/index'],
            ['class'=>'btn text-muted btn-xs']
        ) ?>
  <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Users1s',
             ['user/create', 'User' => ['updated_by' => $model->id]],
            ['class'=>'btn btn-success btn-xs']
        ); ?>
</div>
</div>
<?php Pjax::begin(['id'=>'pjax-Users1s', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-Users1s ul.pagination a, th a']) ?>
<?=
 '<div class="table-responsive">'
 . \yii\grid\GridView::widget([
    'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => $model->getUsers1(),
        'pagination' => [
            'pageSize' => 20,
            'pageParam'=>'page-users1s',
        ]
    ]),
    'pager'        => [
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
        $params[0] = 'user' . '/' . $action;
        $params['User'] = ['updated_by' => $model->primaryKey()[0]];
        return $params;
    },
    'buttons'    => [
        
    ],
    'controller' => 'user'
],
        'id',
        'usr_language',
        'status',
        'username',
        'usr_password',
        'usr_2fasecret',
        'email:email',
			[
                'attribute'=>'usr_sitelevel',
                'value' => function ($model) {
                    return backend\models\User::getUsrSitelevelValueLabel($model->usr_sitelevel);
                }    
            ]        ,
        'usr_sitecsstheme',
]
])
 . '</div>' 
?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


    <?php 
        echo Tabs::widget(
                 [
                     'id' => 'relation-tabs',
                     'encodeLabels' => false,
                     'items' => [
 [
    'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
    'content' => $this->blocks['backend\models\User'],
    'active'  => true,
],
[
    'content' => $this->blocks['Botsignals'],
    'label'   => '<small>Botsignals <span class="badge badge-default">'. $model->getBotsignals()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Botsignals0s'],
    'label'   => '<small>Botsignals0s <span class="badge badge-default">'. $model->getBotsignals0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Botsignals1s'],
    'label'   => '<small>Botsignals1s <span class="badge badge-default">'. $model->getBotsignals1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Categories'],
    'label'   => '<small>Categories <span class="badge badge-default">'. $model->getCategories()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Categories0s'],
    'label'   => '<small>Categories0s <span class="badge badge-default">'. $model->getCategories0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Categories1s'],
    'label'   => '<small>Categories1s <span class="badge badge-default">'. $model->getCategories1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Memberships'],
    'label'   => '<small>Memberships <span class="badge badge-default">'. $model->getMemberships()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Memberships0s'],
    'label'   => '<small>Memberships0s <span class="badge badge-default">'. $model->getMemberships0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Memberships1s'],
    'label'   => '<small>Memberships1s <span class="badge badge-default">'. $model->getMemberships1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Pricelists'],
    'label'   => '<small>Pricelists <span class="badge badge-default">'. $model->getPricelists()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Pricelists0s'],
    'label'   => '<small>Pricelists0s <span class="badge badge-default">'. $model->getPricelists0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Pricelists1s'],
    'label'   => '<small>Pricelists1s <span class="badge badge-default">'. $model->getPricelists1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signallogs'],
    'label'   => '<small>Signallogs <span class="badge badge-default">'. $model->getSignallogs()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signallogs0s'],
    'label'   => '<small>Signallogs0s <span class="badge badge-default">'. $model->getSignallogs0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signallogs1s'],
    'label'   => '<small>Signallogs1s <span class="badge badge-default">'. $model->getSignallogs1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signals'],
    'label'   => '<small>Signals <span class="badge badge-default">'. $model->getSignals()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signals0s'],
    'label'   => '<small>Signals0s <span class="badge badge-default">'. $model->getSignals0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Signals1s'],
    'label'   => '<small>Signals1s <span class="badge badge-default">'. $model->getSignals1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Symbols'],
    'label'   => '<small>Symbols <span class="badge badge-default">'. $model->getSymbols()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Symbols0s'],
    'label'   => '<small>Symbols0s <span class="badge badge-default">'. $model->getSymbols0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Symbols1s'],
    'label'   => '<small>Symbols1s <span class="badge badge-default">'. $model->getSymbols1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userbots'],
    'label'   => '<small>Userbots <span class="badge badge-default">'. $model->getUserbots()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userbots0s'],
    'label'   => '<small>Userbots0s <span class="badge badge-default">'. $model->getUserbots0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userbots1s'],
    'label'   => '<small>Userbots1s <span class="badge badge-default">'. $model->getUserbots1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Usermembers'],
    'label'   => '<small>Usermembers <span class="badge badge-default">'. $model->getUsermembers()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Usermembers0s'],
    'label'   => '<small>Usermembers0s <span class="badge badge-default">'. $model->getUsermembers0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Usermembers1s'],
    'label'   => '<small>Usermembers1s <span class="badge badge-default">'. $model->getUsermembers1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Usermembers2s'],
    'label'   => '<small>Usermembers2s <span class="badge badge-default">'. $model->getUsermembers2()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userpays'],
    'label'   => '<small>Userpays <span class="badge badge-default">'. $model->getUserpays()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userpays0s'],
    'label'   => '<small>Userpays0s <span class="badge badge-default">'. $model->getUserpays0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userpays1s'],
    'label'   => '<small>Userpays1s <span class="badge badge-default">'. $model->getUserpays1()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Userpays2s'],
    'label'   => '<small>Userpays2s <span class="badge badge-default">'. $model->getUserpays2()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Users'],
    'label'   => '<small>Users <span class="badge badge-default">'. $model->getUsers()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Users0s'],
    'label'   => '<small>Users0s <span class="badge badge-default">'. $model->getUsers0()->count() . '</span></small>',
    'active'  => false,
],
[
    'content' => $this->blocks['Users1s'],
    'label'   => '<small>Users1s <span class="badge badge-default">'. $model->getUsers1()->count() . '</span></small>',
    'active'  => false,
],
 ]
                 ]
    );
    ?>
</div>

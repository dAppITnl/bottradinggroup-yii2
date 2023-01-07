<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Bots */

$this->title = $model->bot_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bots-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Bots').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        ['attribute' => 'bot_lock', 'visible' => false],
        'bot_name',
        'bot_3cbotid',
        'bot_dealstartjson:ntext',
        'bot_createdt',
        'bot_updatedt',
        'bot_deletedt',
        'bot_remarks:ntext',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
if($providerSignallogs->totalCount){
    $gridColumnSignallogs = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'siglogUser.id',
                'label' => Yii::t('app', 'Siglog UserId')
            ],
                'siglog_name',
        'siglog_type',
        'siglog_message:ntext',
        'siglog_createdt',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerSignallogs,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Signallogs')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnSignallogs
    ]);
}
?>
    </div>
    
    <div class="row">
<?php
if($providerUsersignals->totalCount){
    $gridColumnUsersignals = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'usrsig_lock',
        [
                'attribute' => 'usrsigUser.id',
                'label' => Yii::t('app', 'Usrsig UserId')
            ],
                'usrsig_name',
        'usrsig_emailtoken:email',
        'usrsig_pair',
        'usrsig_createdt',
        'usrsig_updatedt',
        'usrsig_deletedt',
        'usrsig_remarks:ntext',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerUsersignals,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Usersignals')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnUsersignals
    ]);
}
?>
    </div>
</div>

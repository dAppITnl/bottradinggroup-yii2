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
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Bots').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-4" style="margin-top: 15px">
<?=             
             Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), 
                ['pdf', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                ]
            )?>
            <?= Html::a(Yii::t('app', 'Save As New'), ['save-as-new', 'id' => $model->id], ['class' => 'btn btn-info']) ?>            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
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
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-signallogs']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Signallogs')),
        ],
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
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-usersignals']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Usersignals')),
        ],
        'columns' => $gridColumnUsersignals
    ]);
}
?>

    </div>
</div>

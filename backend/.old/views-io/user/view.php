<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'User').' '. Html::encode($this->title) ?></h2>
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
        'username',
        'user_password',
        'password_hash',
        'password_reset_token',
        'auth_key',
        'email:email',
        'verification_token',
        'status',
        'user_SignalActive',
        'user_moralisId',
        'user_created',
        'user_updated',
        'user_deletedt',
        'user_remarks:ntext',
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
                'attribute' => 'siglogBot.id',
                'label' => Yii::t('app', 'Siglog BotId')
            ],
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
if($providerUserpaids->totalCount){
    $gridColumnUserpaids = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        'usrpay_startdt',
            'usrpay_enddt',
            'usrpay_payamount',
            'usrpay_paysymbol',
            'usrpay_rate',
            'usrpay_ratesymbol',
            'usrpay_paiddt',
            'usrpay_createdt',
            'usrpay_updatedt',
            'usrpay_deletedt',
            'usrpay_remarks:ntext',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerUserpaids,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-userpaids']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Userpaids')),
        ],
        'columns' => $gridColumnUserpaids
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
                        [
                'attribute' => 'usrsigBot.id',
                'label' => Yii::t('app', 'Usrsig BotId')
            ],
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

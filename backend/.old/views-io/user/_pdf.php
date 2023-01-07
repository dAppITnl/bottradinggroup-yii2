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
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'User').' '. Html::encode($this->title) ?></h2>
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
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Userpaids')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
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

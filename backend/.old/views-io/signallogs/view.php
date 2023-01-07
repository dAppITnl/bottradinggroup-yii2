<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Signallogs */

$this->title = $model->siglog_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signallogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signallogs-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Signallogs').' '. Html::encode($this->title) ?></h2>
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
        [
            'attribute' => 'siglogUser.id',
            'label' => Yii::t('app', 'Siglog UserId'),
        ],
        [
            'attribute' => 'siglogBot.id',
            'label' => Yii::t('app', 'Siglog BotId'),
        ],
        'siglog_name',
        'siglog_type',
        'siglog_message:ntext',
        'siglog_createdt',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>Bots<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnBots = [
        ['attribute' => 'id', 'visible' => false],
        'bot_name',
        'bot_3cbotid',
        'bot_dealstartjson',
        'bot_createdt',
        'bot_updatedt',
        'bot_deletedt',
        'bot_remarks',
    ];
    echo DetailView::widget([
        'model' => $model->siglogBot,
        'attributes' => $gridColumnBots    ]);
    ?>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'user_password',
        'password_hash',
        'password_reset_token',
        'auth_key',
        'email',
        'verification_token',
        'status',
        'user_SignalActive',
        'user_moralisId',
        'user_created',
        'user_updated',
        'user_deletedt',
        'user_remarks',
    ];
    echo DetailView::widget([
        'model' => $model->siglogUser,
        'attributes' => $gridColumnUser    ]);
    ?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Usersignals */

$this->title = $model->usrsig_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usersignals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usersignals-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Usersignals').' '. Html::encode($this->title) ?></h2>
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
        ['attribute' => 'usrsig_lock', 'visible' => false],
        [
            'attribute' => 'usrsigUser.id',
            'label' => Yii::t('app', 'Usrsig UserId'),
        ],
        [
            'attribute' => 'usrsigBot.id',
            'label' => Yii::t('app', 'Usrsig BotId'),
        ],
        'usrsig_name',
        'usrsig_emailtoken:email',
        'usrsig_pair',
        'usrsig_createdt',
        'usrsig_updatedt',
        'usrsig_deletedt',
        'usrsig_remarks:ntext',
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
        'model' => $model->usrsigBot,
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
        'model' => $model->usrsigUser,
        'attributes' => $gridColumnUser    ]);
    ?>
</div>

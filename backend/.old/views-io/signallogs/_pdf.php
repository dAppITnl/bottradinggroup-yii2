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
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Signallogs').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'siglogUser.id',
                'label' => Yii::t('app', 'Siglog UserId')
            ],
        [
                'attribute' => 'siglogBot.id',
                'label' => Yii::t('app', 'Siglog BotId')
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
</div>

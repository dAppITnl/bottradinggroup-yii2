<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Usersignals */

?>
<div class="usersignals-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->usrsig_name) ?></h2>
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
</div>
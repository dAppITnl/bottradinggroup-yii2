<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Bots */

?>
<div class="bots-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->bot_name) ?></h2>
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
</div>
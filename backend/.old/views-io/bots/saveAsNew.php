<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bots */

$this->title = Yii::t('app', 'Save As New {modelClass}: ', [
    'modelClass' => 'Bots',
]). ' ' . $model->bot_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bot_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Save As New');
?>
<div class="bots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

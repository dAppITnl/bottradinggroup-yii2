<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Signallogs */

$this->title = Yii::t('app', 'Save As New {modelClass}: ', [
    'modelClass' => 'Signallogs',
]). ' ' . $model->siglog_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signallogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->siglog_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Save As New');
?>
<div class="signallogs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Usersignals */

$this->title = Yii::t('app', 'Save As New {modelClass}: ', [
    'modelClass' => 'Usersignals',
]). ' ' . $model->usrsig_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usersignals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usrsig_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Save As New');
?>
<div class="usersignals-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

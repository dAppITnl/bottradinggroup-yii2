<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Usersignals */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Usersignals',
]) . ' ' . $model->usrsig_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Usersignals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usrsig_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="usersignals-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

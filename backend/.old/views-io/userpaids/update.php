<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Userpaids */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Userpaids',
]) . ' ' . $model->usrpay_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userpaids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usrpay_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="userpaids-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

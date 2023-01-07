<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Signallogs */

$this->title = Yii::t('app', 'Create Signallogs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signallogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signallogs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

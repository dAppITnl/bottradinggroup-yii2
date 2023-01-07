<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bots */

$this->title = Yii::t('app', 'Create Bots');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

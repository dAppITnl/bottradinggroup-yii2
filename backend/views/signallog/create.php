<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var backend\models\Signallog $model
*/

$this->title = Yii::t('models', 'Signallog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Signallogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud signallog-create">

    <h1>
                <?= Html::encode($model->id) ?>
        <small>
            <?= Yii::t('models', 'Signallog') ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            Yii::t('cruds', 'Cancel'),
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>

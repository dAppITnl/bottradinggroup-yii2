<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var backend\models\Symbol $model
*/

$this->title = Yii::t('models', 'Symbol');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Symbols'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud symbol-create">

    <h1>
                <?= Html::encode($model->id) ?>
        <small>
            <?= Yii::t('models', 'Symbol') ?>
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

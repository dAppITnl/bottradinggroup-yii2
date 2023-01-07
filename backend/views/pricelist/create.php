<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var backend\models\Pricelist $model
*/

$this->title = Yii::t('models', 'Pricelist');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Pricelists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud pricelist-create">

    <h1>
                <?= Html::encode($model->id) ?>
        <small>
            <?= Yii::t('models', 'Pricelist') ?>
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

<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var backend\models\Userbot $model
*/

$this->title = Yii::t('models', 'Userbot');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Userbot'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Edit');
?>
<div class="giiant-crud userbot-update">

    <h1>
                <?= Html::encode($model->id) ?>

        <small>
            <?= Yii::t('models', 'Userbot') ?>        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('cruds', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
